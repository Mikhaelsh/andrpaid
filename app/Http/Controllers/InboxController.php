<?php

namespace App\Http\Controllers;

use App\Models\Inbox;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InboxController extends Controller
{
    public function index(){
        $inboxes = Inbox::where("to_user_id", Auth::id())
            ->where("is_sent", true)
            ->with('fromUser')
            ->latest()
            ->paginate(15);

        return view("pages.inboxes", [
            "inboxes" => $inboxes
        ]);
    }

    public function indexDrafts(){
        $draftInboxes = Inbox::where("from_user_id", Auth::id())
            ->where("is_sent", false)
            ->with('toUser')
            ->latest('updated_at')
            ->paginate(15);

        return view("pages.inboxes-draft", [
            "draftInboxes" => $draftInboxes
        ]);
    }

    public function indexSent(){
        $sentInboxes = Inbox::where("from_user_id", Auth::id())
            ->where("is_sent", true)
            ->with('toUser')
            ->latest('updated_at')
            ->paginate(15);

        return view("pages.inboxes-sent", [
            "sentInboxes" => $sentInboxes
        ]);
    }

    public function indexCompose(){
        $inbox = Inbox::create([
            "from_user_id" => Auth::user()->id
        ]);

        return redirect("/inboxes/compose/" . $inbox->inboxId);
    }

    public function indexComposeInboxId($inboxId){
        $inbox = Inbox::where([
            "inboxId" => $inboxId,
            "from_user_id" => Auth::user()->id
        ])->first();

        return view("pages.inboxes-compose", [
            "inbox" => $inbox
        ]);
    }

    public function indexSpecificInbox($inboxId){
        $inbox = Inbox::where('inboxId', $inboxId)
        ->with(['fromUser', 'toUser'])
        ->firstOrFail();

        if (Auth::id() !== $inbox->from_user_id && Auth::id() !== $inbox->to_user_id) {
            return redirect("/inboxes");
        }

        if($inbox->toUser->id === Auth::user()->id && !$inbox->marked_read){
            $inbox->update([
                "marked_read" => true
            ]);
        }

        return view("pages.inbox", [
            "inbox" => $inbox
        ]);
    }

    public function saveOrSendInbox($inboxId, Request $request){
        $inbox = Inbox::where([
            "inboxId" => $inboxId
        ])->first();

        if($request["email"] !== null){
            $user = User::where("email", $request["email"])->first();

            if($user === null){
                return redirect()->back()->with("error", "Fail to send the message. Can't find the recipient");
            }

            $inbox->update([
                "to_user_id" => $user->id
            ]);
        }

        $inbox->update([
            "subject" => $request["subject"],
            "body" => $request["body"],
            "updated_at" => now()
        ]);

        if ($request->input('action') === 'send') {
            if(!$inbox->toUser){
                return redirect()->back()->with("error", "Fail to send the message. Can't find the recipient");
            }

            $inbox->update([
                "is_sent" => true
            ]);

            return redirect("/inboxes/")->with("success", "Your Inbox has been sent successfully");
        } else{
            return redirect("/inboxes/drafts")->with("success", "Your Inbox has been saved successfully");
        }
    }

    public function deleteDraftInbox($inboxId){
        Inbox::where([
            "inboxId" => $inboxId,
            "is_sent" => false
        ])->delete();

        return redirect("/inboxes/drafts")->with("success", "Draft Inbox has been deleted successfully");
    }
}
