(function () {
    const Widget = Object.create({
        create(BotId) {
            const wdg = document.createElement("div");
            wdg.classList.add("chat-popup-inner");
            wdg.innerHTML = `<!-- bot popup window -->
    <div id="chat-circle" class="btn btn-raised">
        <div style="margin: -10px -10px"><i class="fa fa-comment-o fa-2x"></i><br/>Chat</div>
        <div id="chat-overlay"></div>
    </div>
    <div class="chat-box">
        <div class="chat-box-header">
            <h4 class="pull-left">Talk to ` + BotId + `</h4>
            <button type="button" id="chat-box-close" class="chat-box-toggle close" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="chat-box-body">
            <div class="chat-box-overlay"></div>
            <div class="chat-logs"></div><!--chat-log -->
        </div>
        <div class="chat-input">
            <form id="chat-box-form">
                <input type="text" id="chat-input" placeholder="Send a message..." >
                <button type="submit" class="btn btn-sm btn-secondary chat-submit" id="chat-submit">Send</button>
            </form>
        </div>
    </div>
    <!--/bot chat window -->`;
            return wdg;
        }
    });

    const initWhenReady = () => {
        removeEventListener("DOMContentLoaded", initWhenReady);

        const ele = document.querySelector('#chat-popup');
        const botId = ele.dataset.botid;
        const myWidgetInstance = Widget.create(botId);
        ele.appendChild(myWidgetInstance);

        if (ele.dataset.host == '' || ele.dataset.host == 'undefined' || typeof ele.dataset.host === "undefined") {
            ele.dataset.host = '';
        }

        document.getElementById('chat-submit').removeEventListener('click', submitChat);
        document.getElementById('chat-submit').addEventListener('click', submitChat);
        document.getElementById('chat-circle').removeEventListener('click', toggleChat);
        document.getElementById('chat-circle').addEventListener('click', toggleChat);
        document.getElementById('chat-box-close').removeEventListener('click', toggleChat);
        document.getElementById('chat-box-close').addEventListener('click', toggleChat);

        var settings = {
            "url": ele.dataset.host + '/api/talk/meta',
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({"bot": botId}),
        };

        $.ajax(settings).done(function (response) {
            if (ele.dataset.clientimage == '' || typeof ele.dataset.clientimage === "undefined") {
                ele.dataset.clientimage = response.data.client.image || 'default_image.png';
            }
            if (ele.dataset.botimage === '' || typeof ele.dataset.botimage === "undefined") {
                ele.dataset.botimage = response.data.bot.image || 'bot_avatar.png';
            }
        });
    };

    addEventListener('DOMContentLoaded', initWhenReady);

    const submitChat = (e) => {
        e.preventDefault();
        let msg = $("#chat-input").val();
        if (msg.trim() == '') {
            return false;
        }

        const ele = document.querySelector('#chat-popup');
        if (ele.dataset.clientId == '' || ele.dataset.clientImage == 'undefined') {
            ele.dataset.clientId = '';
        }
        if (ele.dataset.html == '' || ele.dataset.html == 'undefined') {
            ele.dataset.html = 1;
        }
        if (ele.dataset.host == '' || ele.dataset.host == 'undefined' || typeof ele.dataset.host === "undefined") {
            ele.dataset.host = '';
        }
        if (ele.dataset.clientimage == '' || typeof ele.dataset.clientimage === "undefined") {
            ele.dataset.clientimage = 'default_image.png';
        }

        $(".chat-logs").generateMessage(msg, ele.dataset.clientimage, 'user');

        const botImage = ele.dataset.botimage || 'bot_avatar.png';
        const typingMsgId = 'cm-msg-' + Date.now();

        const delayBeforeTyping = Math.random() * 1000 + 500; // 500ms to 1500ms

        setTimeout(() => {
            $(".chat-logs").append(`<div id="${typingMsgId}" class="chat-msg bot">
        <span class="msg-avatar"><img src="${botImage}"></span>
        <div class="cm-msg-text typing"></div>
    </div>`);
            $("#" + typingMsgId).hide().fadeIn(300);

            // Start bot response delay (existing code)
            const responseDelay = Math.random() * 2000 + 2000; // 2000ms to 4000ms
            setTimeout(() => {
                $.ajax(settings).done(function (response) {
                    const botResponse = response.data.conversation.output;
                    const botAvatar = response.data.bot.image;
                    $("#" + typingMsgId + " .cm-msg-text").removeClass("typing").html(botResponse);
                    $("#" + typingMsgId + " img").attr('src', botAvatar);

                    $("#chat-popup").attr("data-clientId", response.data.client.id);
                    ele.dataset.botimage = response.data.bot.image || 'bot_avatar.png';
                    if (response.data.client.id) {
                        ele.dataset.clientid = response.data.client.id;
                    }
                });
            }, responseDelay);
        }, delayBeforeTyping);


        var settings = {
            "url": ele.dataset.host + '/api/talk/bot',
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "client": ele.dataset.clientid,
                "bot": ele.dataset.botid,
                "html": ele.dataset.showHtml,
                "message": msg
            }),
        };

        const delay = Math.random() * 1000 + 1000;
        setTimeout(() => {
            $.ajax(settings).done(function (response) {
                const botResponse = response.data.conversation.output;
                const botAvatar = response.data.bot.image;
                $("#" + typingMsgId + " .cm-msg-text").removeClass("typing").html(botResponse);
                $("#" + typingMsgId + " img").attr('src', botAvatar);

                $("#chat-popup").attr("data-clientId", response.data.client.id);
                ele.dataset.botimage = response.data.bot.image || 'bot_avatar.png';
                if (response.data.client.id) {
                    ele.dataset.clientid = response.data.client.id;
                }
            });
        }, delay);
    };

    const toggleChat = () => {
        $("#chat-circle").toggle('scale');
        $(".chat-box").toggle('scale');
    };
})();

$(document).ready(function () {
    var chatMsgIndex = 0;
    (function ($) {
        $.fn.generateMessage = function (msg, image, type) {
            chatMsgIndex++;
            let str = "";
            str += "<div id='cm-msg-" + chatMsgIndex + "' class=\"chat-msg " + type + "\">";
            str += "          <span class=\"msg-avatar\">";
            str += "            <img src=\"" + image + "\">";
            str += "          <\/span>";
            str += "          <div class=\"cm-msg-text\">";
            str += msg;
            str += "          <\/div>";
            str += "        <\/div>";
            $(".chat-logs").append(str);
            $("#cm-msg-" + chatMsgIndex).hide().fadeIn(300);
            if (type == 'user') {
                $("#chat-input").val('');
            }
            $(".chat-logs").stop().animate({scrollTop: $(".chat-logs")[0].scrollHeight}, 1000);
            return this;
        };
    })(jQuery);
});
