@extends('backend.app')

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header text-center">
                <h1 class="page-title">AI Voice Assistant (Voci)</h1>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-lg border-0">
                        <div class="card-body text-center p-5">
                            <div class="mb-4">
                                <span id="status-badge" class="badge bg-secondary-transparent text-secondary px-4 py-3 fs-16">
                                    <i class="fe fe-info me-2"></i>
                                    <span id="status-text">Ready to Connect</span>
                                </span>
                            </div>

                            <!-- Bubble Widget এখানে লোড হবে -->
                            <div id="convai-widget-container" class="mb-5"></div>

                            <div id="visualizer" class="mb-5 d-none">
                                <div class="spinner-grow text-primary" role="status" style="width: 4rem; height: 4rem;"></div>
                                <p class="mt-4 text-primary fw-bold fs-18">
                                    🎤 AI is Listening & Responding...
                                </p>
                            </div>

                            <div class="voice-controls mt-5">
                                <button id="start-btn" class="btn btn-primary btn-lg btn-pill px-6 py-3 shadow me-3">
                                    <i class="fe fe-mic me-2"></i> Start Voice Conversation
                                </button>

                                <button id="stop-btn" class="btn btn-danger btn-lg btn-pill px-6 py-3 shadow d-none">
                                    <i class="fe fe-mic-off me-2"></i> End Session
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ElevenLabs ConvAI Widget Script -->
<script src="https://unpkg.com/@elevenlabs/convai-widget-embed" async defer></script>

<script>
    let currentWidget = null;

    const startBtn     = document.getElementById('start-btn');
    const stopBtn      = document.getElementById('stop-btn');
    const statusText   = document.getElementById('status-text');
    const statusBadge  = document.getElementById('status-badge');
    const visualizer   = document.getElementById('visualizer');
    const container    = document.getElementById('convai-widget-container');

    // ==================== Load Widget ====================
    function loadWidget() {
        container.innerHTML = '';

        const widget = document.createElement('elevenlabs-convai');

        widget.setAttribute('agent-id', 'agent_01jw8gvfrvfr680qewj41n2y98');  // সঠিক Agent ID
        widget.setAttribute('variant', 'bubble');           // bubble style
        widget.setAttribute('dismissible', 'true');
        widget.setAttribute('server-location', 'us');

        container.appendChild(widget);
        currentWidget = widget;

        statusText.innerText = "Connected! Click the bubble & speak";
        statusBadge.classList.replace('bg-secondary-transparent', 'bg-success-transparent');
        statusBadge.classList.replace('text-secondary', 'text-success');
        visualizer.classList.remove('d-none');
    }

    function resetUI() {
        container.innerHTML = '';
        currentWidget = null;

        statusText.innerText = "Ready to Connect";
        statusBadge.classList.remove('bg-success-transparent', 'text-success');
        statusBadge.classList.add('bg-secondary-transparent', 'text-secondary');

        startBtn.classList.remove('d-none');
        startBtn.disabled = false;
        stopBtn.classList.add('d-none');
        visualizer.classList.add('d-none');
    }

    // ==================== Start Button Click ====================
    startBtn.onclick = () => {
        startBtn.disabled = true;
        statusText.innerText = "Connecting to Voci...";

        try {
            loadWidget();

            // Button hide করে Stop button দেখাও
            startBtn.classList.add('d-none');
            stopBtn.classList.remove('d-none');

        } catch (error) {
            console.error(error);
            alert("Failed to start voice: " + error.message);
            resetUI();
        }
    };

    // ==================== Stop Button ====================
    stopBtn.onclick = () => {
        resetUI();
    };

    // Widget Events
    document.addEventListener('elevenlabs:connected', () => {
        console.log('✅ Voci connected successfully');
    });

    document.addEventListener('elevenlabs:error', (e) => {
        console.error('❌ Voci error:', e.detail);
    });
</script>

@endsection
