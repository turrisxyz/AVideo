<button type="button" class="btn btn-default" onclick="openRestream('facebook')">
    <i class="fab fa-facebook-f"></i>
    Facebook
</button>
<button type="button" class="btn btn-default" onclick="openRestream('youtube')">
    <i class="fab fa-youtube"></i>
    YouTube
</button>
<button type="button" class="btn btn-default" onclick="openRestream('twitch')">
    <i class="fab fa-twitch"></i>
    Twitch
</button>

<script>
    var restreamPopupOpened = false;
    // Create browser compatible event handler.
    var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
    var eventer = window[eventMethod];
    var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
    // Listen for a message from the iframe.
    eventer(messageEvent, function (e) {
        console.log('EventListener restreamer', e.data);
        if (e.data.stream_key && e.data.name) {
            saveRestreamer(e.data.stream_key, e.data.stream_url, e.data.name);
        }
    }, false);

    var restreamWin;
    function openRestream(provider) {
        restreamPopupOpened = 1;
        modal.showPleaseWait();
        $('#newLive_restreamsLink').trigger("click");
        restreamWin = window.open("http://localhost/Restreamer/"+provider, "theRestreamerPopUp", "directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,resizable=no,height=600,width=800");
        var pollTimer = window.setInterval(function () {
            if (restreamWin.closed !== false) { // !== is required for compatibility with Opera
                window.clearInterval(pollTimer);
                modal.hidePleaseWait();
                restreamPopupOpened = 0;
                //avideoToast('closed');
            }
        }, 200);
    }

    function saveRestreamer(stream_key, stream_url, name) {
        console.log('saveRestreamer', stream_key, stream_url, name);
        restreamPopupOpened = 0;
        modal.hidePleaseWait();
        if (empty(stream_url)) {
            avideoAlertError(stream_key);
        } else {
            $('#Live_restreamsname').val(name);
            $('#Live_restreamsstatus').val('a');
            $('#Live_restreamsstream_url').val(stream_url);
            $('#Live_restreamsstream_key').val(stream_key);
            $('#panelLive_restreamsForm').submit();
        }
        restreamWin.close();
    }
</script>