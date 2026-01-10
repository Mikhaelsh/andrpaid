@extends('layouts.app')

@section('title', __('researchers.title'))

@section('additionalCSS')
    <link rel="stylesheet" href="{{ asset('styles/researchers.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/leaflet/leaflet.css') }}">
@endsection

@section('content')
    @include('partials.navbarProfile')

    <div class="container-fluid px-4 py-4">
        <div class="row g-4">
            <div class="col-lg-7 col-xl-8">
                <div class="card border-0 shadow-sm p-1 rounded-4 h-100">
                    <div class="card-body p-2 position-relative">
                        <div id="indonesiaMap"></div>

                        <div class="position-absolute bottom-0 start-0 m-4 bg-white p-3 rounded-3 shadow-sm" style="z-index: 500; min-width: 250px;">
                            <span class="text-muted small fw-bold text-uppercase d-block mb-1">{{ __('researchers.selected_region') }}</span>
                            <h5 class="fw-bold text-primary mb-2" id="selectedRegionName">{{ __('researchers.all_indonesia') }}</h5>
                            <button class="btn btn-sm btn-outline-secondary w-100" onclick="resetMap()">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> {{ __('researchers.btn_reset_view') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-xl-4">
                <div class="d-flex flex-column h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="fw-bold mb-0">{{ __('researchers.header_researchers') }}</h4>
                        <span class="badge bg-primary rounded-pill px-3 py-2" id="researcherCountBadge">
                            {{ __('researchers.count_found', ['count' => 0]) }}
                        </span>
                    </div>

                    <div class="reseacher-list-container flex-grow-1" style="min-height: 0; display: flex; flex-direction: column;">

                        <div class="d-flex flex-column gap-3 mb-3" id="researcherGrid">

                        </div>

                        <div id="emptyState" class="text-center py-5 d-none mt-2">
                            <div class="text-muted opacity-50 mb-3">
                                <i class="bi bi-geo-alt" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="fw-bold text-muted">{{ __('researchers.empty_title') }}</h5>
                            <p class="text-muted small">{{ __('researchers.empty_desc') }}</p>
                            <button class="btn btn-link btn-sm" onclick="resetMap()">{{ __('researchers.btn_show_all') }}</button>
                        </div>

                        <div id="paginationControls" class="mt-auto pt-3 border-top d-none">
                            <div class="d-flex justify-content-between align-items-center">
                                <button class="btn btn-outline-secondary btn-sm" id="btnPrev" onclick="changePage(-1)">
                                    <i class="bi bi-chevron-left"></i> {{ __('researchers.btn_prev') }}
                                </button>
                                <span class="text-muted small fw-bold" id="pageIndicator">{{ __('researchers.page_indicator', ['current' => 1, 'total' => 1]) }}</span>
                                <button class="btn btn-outline-secondary btn-sm" id="btnNext" onclick="changePage(1)">
                                    {{ __('researchers.btn_next') }} <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('libs/leaflet/leaflet.js') }}"></script>

<script>
    window.lang = {
        count_found: "{{ __('researchers.count_found', ['count' => ':count']) }}",
        all_indonesia: "{{ __('researchers.all_indonesia') }}",
        page_indicator: "{{ __('researchers.page_indicator', ['current' => ':current', 'total' => ':total']) }}",
        unknown: "{{ __('researchers.unknown') }}"
    };

    document.addEventListener('DOMContentLoaded', function() {
        const ITEMS_PER_PAGE = 5;
        let currentPage = 1;
        let currentFilteredData = [];

        const allResearchers = @json($researchers);
        const gridContainer = document.getElementById('researcherGrid');
        const countBadge = document.getElementById('researcherCountBadge');
        const emptyState = document.getElementById('emptyState');
        const regionLabel = document.getElementById('selectedRegionName');

        const paginationControls = document.getElementById('paginationControls');
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');
        const pageIndicator = document.getElementById('pageIndicator');

        function normalize(str) {
            if (!str) return '';
            let name = str.toLowerCase().trim();
            name = name.replace(/\./g, '');
            const prefixes = ['provinsi ', 'propinsi ', 'daerah istimewa ', 'daerah khusus ibukota ', 'di ', 'dki '];
            prefixes.forEach(prefix => {
                if (name.startsWith(prefix)) name = name.substring(prefix.length);
            });
            name = name.replace(/nusatenggara/g, 'nusa tenggara');
            if (name.includes('bangka') && name.includes('belitung')) return 'bangka belitung';
            if (name.includes('yogya') || name.includes('jogja')) return 'yogyakarta';
            if (name.includes('jakarta')) return 'jakarta';
            return name.trim();
        }

        const provinceCounts = {};
        allResearchers.forEach(r => {
            if (r.province?.name) {
                const pName = normalize(r.province.name);
                provinceCounts[pName] = (provinceCounts[pName] || 0) + 1;
            }
        });

        const map = L.map('indonesiaMap').setView([-2.5489, 118.0149], 5);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);

        let geojsonLayer;
        const geoJsonUrl = "{{ asset('data/indonesia-prov.geojson') }}";

        function initList(data) {
            currentFilteredData = data;
            currentPage = 1;
            countBadge.innerText = window.lang.count_found.replace(':count', data.length);

            if (data.length === 0) {
                gridContainer.innerHTML = '';
                emptyState.classList.remove('d-none');
                paginationControls.classList.add('d-none');
            } else {
                emptyState.classList.add('d-none');
                renderPage();
            }
        }

        function renderPage() {
            gridContainer.innerHTML = '';

            const start = (currentPage - 1) * ITEMS_PER_PAGE;
            const end = start + ITEMS_PER_PAGE;
            const pageData = currentFilteredData.slice(start, end);

            pageData.forEach(researcher => {
                const userName = researcher.user?.name || window.lang.unknown;
                const avatar = `https://ui-avatars.com/api/?name=${userName}&background=random&size=64`;
                const profileLink = `/${researcher.user?.profileId}/overview`;
                const provName = researcher.province?.name || window.lang.unknown;

                let tagsHtml = '';
                if(researcher.research_fields?.length > 0) {
                    researcher.research_fields.slice(0, 3).forEach(field => {
                        tagsHtml += `<span class="badge bg-light text-secondary border small me-1 mb-1">${field.name}</span>`;
                    });
                }

                const cardHtml = `
                    <div class="card border-0 shadow-sm researcher-card animate slideIn">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <img src="${avatar}" class="rounded-circle border" width="50" height="50">
                            <div class="flex-grow-1 overflow-hidden">
                                <h6 class="fw-bold mb-1 text-truncate">
                                    <a href="${profileLink}" class="text-decoration-none text-dark stretched-link">${userName}</a>
                                </h6>
                                <div class="text-muted small mb-2 d-flex align-items-center">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                    <span class="text-truncate">${provName}</span>
                                </div>
                                <div style="line-height:1;">${tagsHtml}</div>
                            </div>
                            <div class="text-end ps-2"><i class="bi bi-chevron-right text-muted"></i></div>
                        </div>
                    </div>
                `;
                gridContainer.innerHTML += cardHtml;
            });

            updatePaginationUI();

            document.querySelector('.reseacher-list-container').scrollTop = 0;
        }

        function updatePaginationUI() {
            const totalPages = Math.ceil(currentFilteredData.length / ITEMS_PER_PAGE);

            if (totalPages <= 1) {
                paginationControls.classList.add('d-none');
            } else {
                paginationControls.classList.remove('d-none');
                pageIndicator.innerText = window.lang.page_indicator.replace(':current', currentPage).replace(':total', totalPages);

                btnPrev.disabled = currentPage === 1;
                btnNext.disabled = currentPage === totalPages;
            }
        }

        window.changePage = function(direction) {
            const totalPages = Math.ceil(currentFilteredData.length / ITEMS_PER_PAGE);
            const newPage = currentPage + direction;

            if (newPage >= 1 && newPage <= totalPages) {
                currentPage = newPage;
                renderPage();
            }
        };


        window.filterResearchers = function(provinceName) {
            if(!provinceName) return;
            regionLabel.innerText = provinceName;
            const targetProv = normalize(provinceName);

            const filtered = allResearchers.filter(r => {
                const researcherProv = normalize(r.province?.name);
                if (!researcherProv) return false;
                return researcherProv === targetProv || researcherProv.includes(targetProv) || targetProv.includes(researcherProv);
            });

            initList(filtered);
        };

        window.resetMap = function() {
            map.setView([-2.5489, 118.0149], 5);
            regionLabel.innerText = window.lang.all_indonesia;
            if(geojsonLayer) {
                geojsonLayer.eachLayer(function (layer) {
                    geojsonLayer.resetStyle(layer);
                    const name = getProvinceName(layer.feature);
                    if(name && provinceCounts[normalize(name)] > 0) layer.setStyle({ fillOpacity: 0.3 });
                });
            }
            initList(allResearchers);
        }

        function getProvinceName(feature) {
            if (feature && feature.properties) {
                const p = feature.properties;
                return p.Propinsi || p.PROVINSI || p.NAME_1 || p.name || p.Name || null;
            }
            return null;
        }

        function style(feature) { return { fillColor: '#0d6efd', weight: 1, opacity: 1, color: 'white', fillOpacity: 0.1 }; }
        function highlightFeature(e) { e.target.setStyle({ weight: 2, color: '#666', fillOpacity: 0.3 }); }
        function resetHighlight(e) { geojsonLayer.resetStyle(e.target); }

        function addCountMarker(feature, layer) {
            const rawName = getProvinceName(feature);
            if (rawName) {
                const geoName = normalize(rawName);
                const count = provinceCounts[geoName];
                if (count && count > 0) {
                    const center = layer.getBounds().getCenter();
                    const countIcon = L.divIcon({
                        className: 'count-icon',
                        html: `<span>${count}</span>`,
                        iconSize: [30, 30],
                        iconAnchor: [15, 15]
                    });
                    const marker = L.marker(center, { icon: countIcon }).addTo(map);
                    marker.on('click', function(e) {
                        L.DomEvent.stopPropagation(e);
                        map.fitBounds(layer.getBounds());
                        filterResearchers(rawName);
                    });
                    layer.setStyle({ fillOpacity: 0.3 });
                }
            }
        }

        fetch(geoJsonUrl)
            .then(res => res.json())
            .then(data => {
                geojsonLayer = L.geoJson(data, {
                    style: style,
                    onEachFeature: function(feature, layer) {
                        const name = getProvinceName(feature);
                        if(name) layer.bindTooltip(name, { sticky: true, className: 'custom-map-tooltip' });
                        layer.on({
                            mouseover: highlightFeature,
                            mouseout: resetHighlight,
                            click: function(e) {
                                map.fitBounds(e.target.getBounds());
                                if(name) filterResearchers(name);
                            }
                        });
                        addCountMarker(feature, layer);
                    }
                }).addTo(map);
            });

        initList(allResearchers);
    });
</script>
@endsection
