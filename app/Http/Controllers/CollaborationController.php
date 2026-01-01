<?php

namespace App\Http\Controllers;

use App\Models\Collaboration;
use App\Models\CollaborationRequest;
use App\Models\Paper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollaborationController extends Controller
{
    public function index($profileId, $paperId){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        $isOwner = Auth::user()->id === $paper->lecturer->user->id;

        $slots = Collaboration::where("paper_id", $paper->id)->get();

        if($isOwner){
            $requests = CollaborationRequest::where([
                ['to_lecturer_id', '=', $user->lecturer->id],
                ['paper_id', '=', $paper->id]
            ])->get();

            $invitations = CollaborationRequest::where([
                ['from_lecturer_id', '=', $user->lecturer->id],
                ['paper_id', '=', $paper->id]
            ])->get();

            return view('pages.paper-collaborations', [
                'user' => $user,
                'paper' => $paper,
                'slots' => $slots,
                'requests' => $requests,
                'invitations' => $invitations,
                'isOwner' => $isOwner,
            ]);
        } else{
            $myPendingInvite = null;
            if (Auth::user()->isLecturer()) {
                $myPendingInvite = CollaborationRequest::where('paper_id', $paper->id)
                    ->where('to_lecturer_id', Auth::user()->lecturer->id)
                    ->where('status', 'pending')
                    ->first();
            }

            return view('pages.paper-collaborations', [
                'user' => $user,
                'paper' => $paper,
                'slots' => $slots,
                'isOwner' => $isOwner,
                "myPendingInvite" => $myPendingInvite
            ]);
        }

    }

    public function toggleCollaboration($profileId, $paperId){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        $paper->update([
            "openCollaboration" => !$paper->openCollaboration
        ]);

        return redirect("/" . $user->profileId . "/paper/" . $paper->paperId . "/collaborations");
    }

    public function createNewRole($profileId, $paperId, Request $request){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        Collaboration::create([
            "role" => $request["role"],
            "description" => $request["description"],
            "paper_id" => $paper->id
        ]);

        return redirect("/" . $user->profileId . "/paper/" . $paper->paperId . "/collaborations#roles")->with('success', 'New role has been created successfully!');
    }

    public function removeRole($profileId, $paperId, Request $request){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        Collaboration::where("id", $request["roleId"])->delete();

        return redirect("/" . $user->profileId . "/paper/" . $paper->paperId . "/collaborations#roles")->with('success', 'Role has been removed successfully!');
    }

    public function inviteUser($profileId, $paperId, Request $request){
        $fromUser = User::where("profileId", $profileId)->firstOrFail();
        $toUser = User::where("id", $request["user_id"])->first();

        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        $collaboration = Collaboration::where("id", $request["slot_id"])->first();

        CollaborationRequest::create([
            "from_lecturer_id" => $fromUser->lecturer->id,
            "to_lecturer_id" => $toUser->lecturer->id,
            "collaboration_id" => $collaboration->id,
            "paper_id" => $paper->id
        ]);

        return redirect("/" . $fromUser->profileId . "/paper/" . $paper->paperId . "/collaborations#invitations")->with('success', 'Invitation has been sent successfully!');
    }

    public function cancelInvitation($profileId, $paperId, Request $request){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        $req = CollaborationRequest::where("id", $request["invitation_id"])->first();

        if($req->status === "pending"){
            $message = "Invitation has been cancelled successfully!";
        } else{
            $message = "Invitation has been removed successfully!";
        }

        CollaborationRequest::where("id", $request["invitation_id"])->delete();

        return redirect("/" . $user->profileId . "/paper/" . $paper->paperId . "/collaborations#invitations")->with('success', $message);
    }

    public function clearInvitationHistory($profileId, $paperId){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        CollaborationRequest::where('paper_id', $paper->id)
        ->where('from_lecturer_id', $paper->lecturer->id)
        ->whereIn('status', ['accepted', 'rejected'])
        ->delete();

        return redirect("/" . $user->profileId . "/paper/" . $paper->paperId . "/collaborations#invitations")->with('success', 'Invitation History has been cleared successfully!');
    }

    public function acceptInvitation($profileId, $paperId, Request $request){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        $collaborationRequest = CollaborationRequest::where('id', $request["invitation_id"])->first();
        $collaborationRequest->update([
            "status" => "accepted"
        ]);

        $collaboration = Collaboration::where("id", $collaborationRequest->collaboration->id)->first();
        $collaboration->update([
            "lecturer_id" => Auth::user()->lecturer->id
        ]);

        return redirect("/" . $user->profileId . "/paper/" . $paper->paperId . "/collaborations#invitations")->with('success', 'Role has been accepted successfully! Welcome!');
    }

    public function rejectInvitation($profileId, $paperId, Request $request){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        $collaborationRequest = CollaborationRequest::where('id', $request["invitation_id"])->first();
        $collaborationRequest->update([
            "status" => "rejected",
            "message" => $request["message"]
        ]);

        return redirect("/" . $user->profileId . "/paper/" . $paper->paperId . "/collaborations#invitations")->with('success', 'Role has been rejected successfully!');
    }

    public function acceptRequest($profileId, $paperId, Request $request){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        $collaborationRequest = CollaborationRequest::where('id', $request["requestId"])->first();
        $collaborationRequest->update([
            "status" => "accepted"
        ]);

        $collaboration = Collaboration::where("id", $collaborationRequest->collaboration->id)->first();
        $collaboration->update([
            "lecturer_id" =>$collaborationRequest->fromLecturer->id
        ]);

        return redirect("/" . $user->profileId . "/paper/" . $paper->paperId . "/collaborations#roles")->with('success', 'Request has been accepted successfully!');
    }

    public function rejectRequest($profileId, $paperId, Request $request){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        $collaborationRequest = CollaborationRequest::where('id', $request["requestId"])->first();
        $collaborationRequest->update([
            "status" => "rejected"
        ]);

        // Inbox message
        // "message" => "Expertise does not match with us. Sorry"

        return redirect("/" . $user->profileId . "/paper/" . $paper->paperId . "/collaborations#roles")->with('success', 'Request has been rejected successfully!');
    }

    public function removeRequest($profileId, $paperId, Request $request){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        CollaborationRequest::where('id', $request["requestId"])->delete();

        return redirect("/" . $user->profileId . "/paper/" . $paper->paperId . "/collaborations#roles")->with('success', 'Request has been removed successfully!');
    }

    public function clearRequestHistory($profileId, $paperId){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        CollaborationRequest::where('paper_id', $paper->id)
        ->where('to_lecturer_id', $paper->lecturer->id)
        ->whereIn('status', ['accepted', 'rejected'])
        ->delete();

        return redirect("/" . $user->profileId . "/paper/" . $paper->paperId . "/collaborations#invitations")->with('success', 'Request History has been cleared successfully!');
    }

    public function editRole($profileId, $paperId, Request $request){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        $collaboration = Collaboration::where('id', $request["slot_id"])->first();

        $collaboration->update([
            "role" => $request["role"],
            "description" => $request["description"]
        ]);

        return redirect("/" . $user->profileId . "/paper/" . $paper->paperId . "/collaborations#roles")->with('success', 'Role has been edited successfully!');
    }

    public function removeMember($profileId, $paperId, Request $request){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        $collaboration = Collaboration::where('id', $request["slot_id"])->first();
        $collaboration->update([
            "lecturer_id" => null,
        ]);

        // message goes to inbox
        // $request["reason"]

        return redirect("/" . $user->profileId . "/paper/" . $paper->paperId . "/collaborations#roles")->with('success', 'Member has been removed successfully!');
    }

    public function applyForRole($profileId, $paperId, Request $request){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        CollaborationRequest::create([
            "from_lecturer_id" => Auth::user()->lecturer->id,
            "to_lecturer_id" => $user->lecturer->id,
            "collaboration_id" => $request["slot_id"],
            "paper_id" => $paper->id,
            "message" => $request["message"]
        ]);

        return redirect("/" . $user->profileId . "/paper/" . $paper->paperId . "/collaborations#roles")->with('success', 'Your Request has been sent successfully!');
    }
}
