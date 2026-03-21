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
                                    <i class="fe fe-info me-2"></i> <span id="status-text">Ready to Connect</span>
                                </span>
                            </div>

                            <!-- ElevenLabs ConvAI Widget এখানে লোড হবে -->
                            <div id="convai-widget-container" class="mb-5">
                                <!-- Widget dynamically load হবে JS দিয়ে -->
                            </div>

                            <div id="visualizer" class="mb-5 d-none">
                                <div class="spinner-grow text-primary" role="status" style="width: 4rem; height: 4rem;"></div>
                                <p class="mt-4 text-primary fw-bold animate__animated animate__pulse animate__infinite fs-18">
                                    AI is Listening & Responding...
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

<!-- ElevenLabs ConvAI Widget Script (latest embed way) -->
<script src="https://unpkg.com/@elevenlabs/convai-widget-embed" async type="text/javascript"></script>

<script>
    let widget = null;

    const startBtn = document.getElementById('start-btn');
    const stopBtn = document.getElementById('stop-btn');
    const statusText = document.getElementById('status-text');
    const statusBadge = document.getElementById('status-badge');
    const visualizer = document.getElementById('visualizer');
    const container = document.getElementById('convai-widget-container');

    async function loadWidget(signedUrl) {
        // Widget container খালি করো
        container.innerHTML = '';

        // Create <elevenlabs-convai> element
        const convaiElement = document.createElement('elevenlabs-convai');
        convaiElement.setAttribute('signed-url', signedUrl);
        // convaiElement.setAttribute('agent-id', 'agent_01jw8gvfrvfr680qewj41n2y98'); // যদি public হয় তাহলে এটা ব্যবহার করো, signed-url না
        convaiElement.setAttribute('variant', 'expanded');     // expanded / compact / bubble — চেষ্টা করো যেটা ভালো লাগে
        convaiElement.setAttribute('dismissible', 'true');     // minimize করা যাবে
        convaiElement.setAttribute('server-location', 'us');   // বা 'eu' যদি EU region হয়

        container.appendChild(convaiElement);

        // Widget load হওয়ার পর status update
        statusText.innerText = "Widget Loaded – Click Start or Speak";
        visualizer.classList.remove('d-none');
    }

    startBtn.onclick = async () => {
        try {
            startBtn.disabled = true;
            statusText.innerText = "Fetching Secure Connection...";
            statusBadge.classList.replace('bg-secondary-transparent', 'bg-warning-transparent');
            statusBadge.classList.replace('text-secondary', 'text-warning');

            // Backend থেকে signed URL নাও
            const response = await fetch("{{ route('admin.cms.home.test.voice.signed_url') }}", {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) {
                throw new Error(`Backend error: ${response.status}`);
            }

            const data = await response.json();

            if (!data.success || !data.signed_url) {
                throw new Error(data.message || data.error || "No signed_url received");
            }

            // Widget load করো signed URL দিয়ে
            await loadWidget(data.signed_url);

            statusText.innerText = "Connected! Start Speaking...";
            statusBadge.classList.replace('bg-warning-transparent', 'bg-success-transparent');
            statusBadge.classList.replace('text-warning', 'text-success');

            startBtn.classList.add('d-none');
            stopBtn.classList.remove('d-none');

        } catch (error) {
            console.error("Error:", error);
            alert("Connection Failed: " + error.message);
            resetUI();
        }
    };

    stopBtn.onclick = () => {
        // Widget remove করে reset
        container.innerHTML = '';
        resetUI();
    };

    function resetUI() {
        statusText.innerText = "Ready to Connect";
        statusBadge.classList.remove('bg-success-transparent', 'bg-warning-transparent');
        statusBadge.classList.add('bg-secondary-transparent');
        statusBadge.classList.remove('text-success', 'text-warning');
        statusBadge.classList.add('text-secondary');

        startBtn.classList.remove('d-none');
        startBtn.disabled = false;
        stopBtn.classList.add('d-none');
        visualizer.classList.add('d-none');
    }

    // Optional: Widget থেকে event listen করতে চাইলে (advanced)
    document.addEventListener('elevenlabs:connected', () => {
        console.log('ElevenLabs widget connected!');
    });
    document.addEventListener('elevenlabs:error', (e) => {
        console.error('ElevenLabs error:', e.detail);
    });
</script>

@endsection
