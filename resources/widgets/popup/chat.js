(function() {
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
                <h4 class="pull-left">Talk to `+BotId+`</h4>
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
                // Load your chat data into UI
                return wdg;
            }
        });
        const initWhenReady = () => {
            removeEventListener("DOMContentLoaded", initWhenReady);

            const ele = document.querySelector('#chat-popup');
            const botId = ele.dataset.botid;
            const myWidgetInstance = Widget.create(botId);
            ele.appendChild(myWidgetInstance);


            if(ele.dataset.host==''|| ele.dataset.host == 'undefined' || typeof ele.dataset.host  === "undefined"){
                ele.dataset.host = '';
            }

            document.getElementById('chat-submit').removeEventListener('click', submitChat);
            document.getElementById('chat-submit').addEventListener('click', submitChat);
            document.getElementById('chat-circle').removeEventListener('click', toggleChat);
            document.getElementById('chat-circle').addEventListener('click', toggleChat);
            document.getElementById('chat-box-close').removeEventListener('click', toggleChat);
            document.getElementById('chat-box-close').addEventListener('click', toggleChat);

            //get the default bot settings from the api
            var settings = {
                "url": ele.dataset.host+'/api/talk/meta',
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({"bot":botId}),
            };

            $.ajax(settings).done(function (response) {

                const ele = document.querySelector('#chat-popup');

                if(ele.dataset.clientimage=='' || typeof ele.dataset.clientimage  === "undefined"){
                    if(response.data.client.image=='' || typeof response.data.client.image === "undefined"){
                        ele.dataset.clientimage = 'data:image/png;base64,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';
                    }else{
                        ele.dataset.clientimage =response.data.client.image;
                    }
                }
            });
        };

        addEventListener('DOMContentLoaded', initWhenReady);

        const submitChat = (e) => {
            e.preventDefault();
            let msg = $("#chat-input").val();
            if(msg.trim() == ''){
                return false;
            }

            const ele = document.querySelector('#chat-popup');

            //set default...
            if(ele.dataset.clientId==''|| ele.dataset.clientImage == 'undefined' ){
                ele.dataset.clientId==''
            }
            if(ele.dataset.html==''|| ele.dataset.html == 'undefined' ){
                ele.dataset.html = 1;
            }

            if(ele.dataset.host==''|| ele.dataset.host == 'undefined' || typeof ele.dataset.host  === "undefined"){
                ele.dataset.host = '';
            }

            if(ele.dataset.clientimage=='' || typeof ele.dataset.clientimage  === "undefined"){
                ele.dataset.clientimage = 'data:image/png;base64,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';
            }

            $(".chat-logs").generateMessage(msg, ele.dataset.clientimage,'user');

            var settings = {
                "url": ele.dataset.host+'/api/talk/bot',
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({"client":ele.dataset.clientid,"bot":ele.dataset.botid,"html":ele.dataset.showHtml,"message":msg}),
            };

            $.ajax(settings).done(function (response) {
                $("#chat-popup").attr("data-clientId",response.data.client.id);
                $(".chat-logs").generateMessage(response.data.conversation.output,response.data.bot.image, 'bot');

                //update the clientId for the next time
                if(response.data.client.id==='' || typeof response.data.client.id === "undefined"){
                    console.error('unable to obtain client.id')
                }else{
                    ele.dataset.clientid =response.data.client.id;
                }

            });
        };

        const toggleChat = () => {
            $("#chat-circle").toggle('scale');
            $(".chat-box").toggle('scale');
        };

    })();

$( document ).ready(function() {

    //global
    var chatMsgIndex = 0;

    (function( $ ){
        $.fn.generateMessage = function(msg, image, type) {

            console.log(msg, image, type)

            chatMsgIndex++;
            let str="";
            str += "<div id='cm-msg-"+chatMsgIndex+"' class=\"chat-msg "+type+"\">";
            str += "          <span class=\"msg-avatar\">";
            str += "            <img src=\""+image+"\">";
            str += "          <\/span>";
            str += "          <div class=\"cm-msg-text\">";
            str += msg;
            str += "          <\/div>";
            str += "        <\/div>";
            $(".chat-logs").append(str);
            $("#cm-msg-"+chatMsgIndex).hide().fadeIn(300);
            if(type == 'user'){
                $("#chat-input").val('');
            }
            $(".chat-logs").stop().animate({ scrollTop: $(".chat-logs")[0].scrollHeight}, 1000);
            return this;
        };
    })( jQuery );

})
