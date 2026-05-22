<!DOCTYPE html>
<html>

@php
    $guest = request()->query('guest');

    $bgImage = asset('assets/img/default.png');

    if ($guest === 'doctor') {
        $bgImage = asset('assets/img/82553036622889933526.png');
    } else {
        $bgImage = asset('assets/img/58069052026608580052.png');
    }
@endphp

<head>

    <title>Video Call | MedNero</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Agora SDK -->
    <script src="https://download.agora.io/sdk/release/AgoraRTC_N.js"></script>

    <style>

        body{
            background: linear-gradient(180deg, #33b7ff 0%, #0070f3 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .main-logo{
            max-width: 220px;
        }

        .video-remote-wrap{
            width: 600px;
            height: 380px;
            background: url("{{ $bgImage }}") no-repeat center;
            background-size: cover;
            margin: 0 auto;
            border-radius: 24px;
            overflow: hidden;
            border: 3px solid #fff;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,.2);
        }

        .remote-video {
            width: 100% !important;
            height: 100% !important;
        }

        #remote video,
        #local video{
            object-fit: cover !important;
        }

        #local {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 160px;
            height: 120px;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #fff;
            z-index: 10;
            background: #000;
        }

        .status-badge{
            display:inline-block;
            padding:8px 18px;
            border-radius:30px;
            background:#ffffff20;
            color:#fff;
            font-weight:600;
            margin-bottom:15px;
            backdrop-filter: blur(8px);
        }

        @media(max-width:768px){

            .video-remote-wrap{
                width:100%;
                height:65vh;
            }

            #local{
                width:120px;
                height:90px;
            }
        }

    </style>

</head>

<body>

<div class="container text-center py-5">

    <img class="main-logo mb-4"
         src="{{ asset('assets/img/logo-mednero-white.png') }}">

    <div class="status-badge" id="callStatus">
        Ready to connect
    </div>

    <h5 class="text-white mb-4">
        Channel: {{ $channel }}
    </h5>

    <!-- VIDEO AREA -->
    <div class="video-remote-wrap">

        <div id="remote"
             style="width:100%; height:100%;">
        </div>

        <div id="local"></div>

    </div>

    <br>

    <!-- BUTTONS -->
    <button id="startBtn"
            class="btn btn-success btn-lg px-5 me-2">
        Start Call
    </button>

    <button id="endBtn"
            class="btn btn-danger btn-lg px-5 d-none">
        End Call
    </button>

</div>

<script>

    // =========================================
    // CONFIG
    // =========================================
    const channel = "{{ $channel }}";

    const guest = "{{ request()->query('guest') }}";

    // IMPORTANT
    // NUMERIC UID
    const uid = Math.floor(Math.random() * 1000000);

    // TOKEN URL
    const tokenUrl =
        "{{ url('/agora/token') }}/" + channel + "/" + uid;

    // AGORA CLIENT
    const client = AgoraRTC.createClient({
        mode: "rtc",
        codec: "vp8"
    });

    // TRACKS
    let localTracks = [];

    // FLAGS
    let joined = false;
    let endingCall = false;

    // ELEMENTS
    const startBtn = document.getElementById("startBtn");
    const endBtn = document.getElementById("endBtn");
    const callStatus = document.getElementById("callStatus");


    // =========================================
    // STATUS
    // =========================================
    function setStatus(text) {

        callStatus.innerText = text;
    }


    // =========================================
    // SUBSCRIBE USER
    // =========================================
    const subscribeUser = async (user, mediaType) => {

        try {

            await client.subscribe(user, mediaType);

            // VIDEO
            if (mediaType === "video") {

                let container =
                    document.getElementById(user.uid);

                if (!container) {

                    container =
                        document.createElement("div");

                    container.id = user.uid;

                    container.className = "remote-video";

                    document.getElementById("remote")
                        .appendChild(container);
                }

                user.videoTrack.play(container);
            }

            // AUDIO
            if (mediaType === "audio") {

                try {

                    user.audioTrack.play();

                } catch (e) {

                    console.warn("Audio blocked:", e);
                }
            }

        } catch (e) {

            console.error("Subscribe Error:", e);
        }
    };


    // =========================================
    // START CALL
    // =========================================
    async function startCall() {

        if (joined) return;

        try {

            startBtn.disabled = true;

            startBtn.innerText = "Connecting...";

            setStatus("Connecting...");

            // =====================================
            // EVENTS
            // =====================================

            client.on(
                "user-published",
                subscribeUser
            );

            client.on(
                "user-unpublished",
                (user) => {

                    const el =
                        document.getElementById(user.uid);

                    if (el) el.remove();
                }
            );

            client.on(
                "user-left",
                (user) => {

                    const el =
                        document.getElementById(user.uid);

                    if (el) el.remove();
                }
            );

            client.on(
                "connection-state-change",
                (cur, prev) => {

                    console.log(
                        "Connection:",
                        prev,
                        "→",
                        cur
                    );

                    setStatus(cur);
                }
            );

            // =====================================
            // GET TOKEN
            // =====================================

            const res = await fetch(tokenUrl);

            const data = await res.json();

            console.log("UID:", uid);
            console.log("TOKEN:", data);

            // =====================================
            // JOIN CHANNEL
            // =====================================

            await client.join(
                data.app_id,
                channel,
                data.token,
                uid
            );

            // =====================================
            // CREATE TRACKS
            // =====================================

            localTracks =
                await AgoraRTC
                    .createMicrophoneAndCameraTracks()
                    .catch(err => {

                        alert(
                            "Please allow camera & microphone access"
                        );

                        throw err;
                    });

            // =====================================
            // PLAY LOCAL VIDEO
            // =====================================

            localTracks[1].play("local");

            // =====================================
            // PUBLISH TRACKS
            // =====================================

            await client.publish(localTracks);

            // =====================================
            // IMPORTANT
            // START RECORDING ONLY
            // AFTER PUBLISH
            // =====================================

            await fetch(
                "{{ url('/start-call') }}",
                {

                    method: "POST",

                    headers: {
                        "Content-Type":
                            "application/json",

                        "X-CSRF-TOKEN":
                            "{{ csrf_token() }}"
                    },

                    body: JSON.stringify({
                        channel: channel,
                        guest: guest
                    })
                }
            );
                   
            // =====================================
            // EXISTING USERS
            // =====================================

            client.remoteUsers.forEach(user => {

                if (user.videoTrack) {

                    subscribeUser(user, "video");
                }

                if (user.audioTrack) {

                    subscribeUser(user, "audio");
                }
            });

            // =====================================
            // SAFETY RETRY
            // =====================================

            setTimeout(() => {

                client.remoteUsers.forEach(user => {

                    if (user.videoTrack) {

                        subscribeUser(user, "video");
                    }

                    if (user.audioTrack) {

                        subscribeUser(user, "audio");
                    }
                });

            }, 1000);

            // =====================================
            // SUCCESS
            // =====================================

            joined = true;

            setStatus("Call Connected");

            startBtn.classList.add("d-none");

            endBtn.classList.remove("d-none");

        } catch (err) {

            console.error(err);

            alert(err.message || "Call failed");

            setStatus("Connection Failed");

            startBtn.disabled = false;

            startBtn.innerText = "Start Call";
        }
    }


    // =========================================
    // LEAVE CALL
    // =========================================
    async function leaveCall() {

        if (endingCall) return;

        endingCall = true;

        try {

            setStatus("Ending Call...");

            // =====================================
            // STOP RECORDING
            // =====================================

            await fetch(
                "{{ url('/end-call-status') }}",
                {

                    method: "POST",

                    headers: {
                        "Content-Type":
                            "application/json",

                        "X-CSRF-TOKEN":
                            "{{ csrf_token() }}"
                    },

                    body: JSON.stringify({
                        channel: channel
                    })
                }
            );

            // =====================================
            // STOP TRACKS
            // =====================================

            localTracks.forEach(track => {

                track.stop();

                track.close();
            });

            // =====================================
            // LEAVE CHANNEL
            // =====================================

            await client.leave();

            // =====================================
            // RESET
            // =====================================

            joined = false;

            document.getElementById("remote")
                .innerHTML = "";

            document.getElementById("local")
                .innerHTML = "";

            startBtn.classList.remove("d-none");

            endBtn.classList.add("d-none");

            startBtn.disabled = false;

            startBtn.innerText = "Start Call";

            setStatus("Call Ended");

        } catch (err) {

            console.error(err);

        } finally {

            endingCall = false;
        }
    }


    // =========================================
    // AUTO END ON TAB CLOSE
    // =========================================
    window.addEventListener(
        "beforeunload",
        function () {

            navigator.sendBeacon(

                "{{ url('/end-call-status') }}",

                new Blob(
                    [
                        JSON.stringify({
                            channel: channel
                        })
                    ],
                    {
                        type: "application/json"
                    }
                )
            );
        }
    );


    // =========================================
    // BUTTONS
    // =========================================
    startBtn.onclick = startCall;

    endBtn.onclick = leaveCall;

</script>

</body>
</html>