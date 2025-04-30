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
            <h4 class="pull-left">Talk to ${BotId}</h4>
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

        if (!ele.dataset.host || ele.dataset.host === 'undefined') {
            ele.dataset.host = '';
        }

        document.getElementById('chat-submit').removeEventListener('click', submitChat);
        document.getElementById('chat-submit').addEventListener('click', submitChat);
        document.getElementById('chat-circle').removeEventListener('click', toggleChat);
        document.getElementById('chat-circle').addEventListener('click', toggleChat);
        document.getElementById('chat-box-close').removeEventListener('click', toggleChat);
        document.getElementById('chat-box-close').addEventListener('click', toggleChat);

        // meta call for images, etc.
        const metaSettings = {
            url: ele.dataset.host + '/api/talk/meta',
            method: "POST",
            timeout: 0,
            headers: { "Content-Type": "application/json" },
            data: JSON.stringify({ bot: botId }),
        };

        $.ajax(metaSettings).done(function (response) {
            ele.dataset.clientimage = ele.dataset.clientimage || response.data.client.image || 'default_image.png';
            ele.dataset.botimage    = ele.dataset.botimage    || response.data.bot.image    || 'bot_avatar.png';
        });
    };

    addEventListener('DOMContentLoaded', initWhenReady);

    const submitChat = (e) => {
        e.preventDefault();
        const msg = $("#chat-input").val().trim();
        if (!msg) return false;

        const ele = document.querySelector('#chat-popup');
        const clientImg = ele.dataset.clientimage || 'default_image.png';
        const botImg    = ele.dataset.botimage    || 'bot_avatar.png';

        // show user's message
        $(".chat-logs").generateMessage(msg, clientImg, 'user');

        // prepare single bot request
        const botSettings = {
            url: ele.dataset.host + '/api/talk/bot',
            method: "POST",
            timeout: 0,
            headers: { "Content-Type": "application/json" },
            data: JSON.stringify({
                client: ele.dataset.clientid || '',
                bot:    ele.dataset.botid,
                html:   ele.dataset.showHtml,
                message: msg
            }),
        };

        // typing indicator
        const typingMsgId = 'cm-msg-' + Date.now();
        const delayBeforeTyping = Math.random() * 1000 + 500; // 500–1500ms

        setTimeout(() => {
            $(".chat-logs").append(`
                <div id="${typingMsgId}" class="chat-msg bot">
                    <span class="msg-avatar"><img src="${botImg}"></span>
                    <div class="cm-msg-text typing"></div>
                </div>
            `);
            $(`#cm-msg-${typingMsgId}`).hide().fadeIn(300);

            const responseDelay = Math.random() * 2000 + 2000; // 2000–4000ms
            setTimeout(() => {
                $.ajax(botSettings).done(function (response) {
                    const out    = response.data.conversation.output;
                    const avatar = response.data.bot.image;
                    $(`#${typingMsgId} .cm-msg-text`).removeClass("typing").html(out);
                    $(`#${typingMsgId} img`).attr('src', avatar);

                    if (response.data.client.id) {
                        ele.dataset.clientid = response.data.client.id;
                    }
                    ele.dataset.botimage = avatar;
                });
            }, responseDelay);
        }, delayBeforeTyping);

        // clear input
        $("#chat-input").val('');
    };

    const toggleChat = () => {
        $("#chat-circle").toggle('scale');
        $(".chat-box").toggle('scale');
    };
})();

$(document).ready(function () {
    let chatMsgIndex = 0;
    (function ($) {
        $.fn.generateMessage = function (msg, image, type) {
            chatMsgIndex++;
            let str = "";
            str += "<div id='cm-msg-" + chatMsgIndex + "' class=\"chat-msg " + type + "\">";
            str += "<span class=\"msg-avatar\"><img src=\"" + image + "\"></span>";
            str += "<div class=\"cm-msg-text\">" + msg + "</div>";
            str += "</div>";
            $(".chat-logs").append(str);
            $(`#cm-msg-${chatMsgIndex}`).hide().fadeIn(300);
            if (type === 'user') {
                $("#chat-input").val('');
            }
            $(".chat-logs").stop().animate({ scrollTop: $(".chat-logs")[0].scrollHeight }, 1000);
            return this;
        };
    })(jQuery);
});
