(function() {
    const Widget = Object.create({
        create() {

            const wdg = document.createElement("div");
            wdg.classList.add("chat-avatar-inner");
            wdg.innerHTML = `<!-- bot popup window -->
                <div class="lemur-main-box clearfix">
                   

                    <div id="animated-bot-avatar-default" class="profile-img img-responsive center-block"></div>
                    <div id="animated-bot-avatar-talking-1" class="talking-head profile-img img-responsive center-block" style="display:none"></div>
                    <div id="animated-bot-avatar-talking-2" class="talking-head profile-img img-responsive center-block" style="display:none"></div>
                    <div id="animated-bot-avatar-talking-3" class="talking-head profile-img img-responsive center-block" style="display:none"></div>
                    <div id="animated-bot-avatar-waiting-1" class="talking-head profile-img img-responsive center-block"style="display:none"></div>
                    <div id="animated-bot-avatar-waiting-2" class="talking-head profile-img img-responsive center-block" style="display:none"></div>


                        <div class="lemur-conversation-wrapper">
                            <div class="lemur-conversation-content">
                                <div class="slimScrollDiv chat-window" id="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 210px;">
                                    <div class="lemur-conversation-inner" id="lemur-conversation-inner" style=" height: 210px;">
                                    </div>
                                </div>
                            </div>
                            <div class="lemur-conversation-new-message">
                                <form id="chat-form">
                                
                                <div class="input-group input-group-sm"">
                                  <input type="text" id="message" name="message" class="form-control" rows="2" placeholder="say...">
                                  <div class="input-group-append input-group-btn">
                                    <button type="submit" id="send-chat" class="btn btn-success" type="button">Send</button>
                                  </div>
                                </div>
                                
                                   

                                </form>
                            </div>
                    </div>
                </div>
        <!--/bot chat window -->`;
            // Load your chat data into UI
            return wdg;
        }
    });
    const initWhenReady = () => {
        removeEventListener("DOMContentLoaded", initWhenReady);

        const ele = document.querySelector('#chat-avatar');
        const botId = ele.dataset.botid;
        const myWidgetInstance = Widget.create(botId);
        ele.appendChild(myWidgetInstance);

        document.getElementById('chat-form').removeEventListener('click', submitChat);
        document.getElementById('chat-form').addEventListener('click', submitChat);

        if(ele.dataset.host==''|| ele.dataset.host == 'undefined' || typeof ele.dataset.host  === "undefined"){
            ele.dataset.host = '';
        }

        //get the default bot settings from the api
        var settings = {
            "url": ele.dataset.host+'/api/talk/meta',
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({"bot":ele.dataset.botid}),
        };

        $.ajax(settings).done(function (response) {

            let lemurtarObj = response.data.bot.lemurtar;
            console.log(lemurtarObj)
            lemurtarObj.style='transparent';

            $('#animated-bot-avatar-default').animateAvatar(lemurtarObj)
            lemurtarObj.mouth='open';
            lemurtarObj.eyebrows='updown';
            lemurtarObj.eyes='closed';
            $('#animated-bot-avatar-talking-1').animateAvatar(lemurtarObj)
            lemurtarObj.mouth='smile';
            lemurtarObj.eyebrows='default';
            lemurtarObj.eyes='open';
            $('#animated-bot-avatar-talking-2').animateAvatar(lemurtarObj)
            lemurtarObj.mouth='twinkle';
            lemurtarObj.eyebrows='updown';
            lemurtarObj.eyes='open';
            $('#animated-bot-avatar-talking-3').animateAvatar(lemurtarObj)
            lemurtarObj.mouth='smile';
            lemurtarObj.eyebrows='default';
            lemurtarObj.eyes='side';
            $('#animated-bot-avatar-waiting-1').animateAvatar(lemurtarObj)
            lemurtarObj.mouth='smile';
            lemurtarObj.eyebrows='default';
            lemurtarObj.eyes='open';
            $('#animated-bot-avatar-waiting-2').animateAvatar(lemurtarObj)


            const ele = document.querySelector('#chat-avatar');

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
        let msg = $("#message").val();
        if(msg.trim() == ''){
            return false;
        }

        $('#send-chat').prop('disabled', true);

        const ele = document.querySelector('#chat-avatar');

        //set default...
        if(ele.dataset.clientId==''|| ele.dataset.clientImage == 'undefined' ){
            ele.dataset.clientId==''
        }
        if(ele.dataset.html==''|| ele.dataset.html == 'undefined' ){
            ele.dataset.html = 1;
        }

        if(ele.dataset.clientimage=='' || typeof ele.dataset.clientimage  === "undefined"){
            ele.dataset.clientimage = 'data:image/png;base64,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';
        }

        if(ele.dataset.host==''|| ele.dataset.host == 'undefined' || typeof ele.dataset.host  === "undefined"){
            ele.dataset.host = '';
        }

        $("#lemur-conversation-inner").generateUserMessage(msg, ele.dataset.clientimage,'user');

        $("#message").val('');


        $.ajax({
            "url": ele.dataset.host+'/api/talk/bot',
            cache: false,
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({"client":ele.dataset.clientid,"bot":ele.dataset.botid,"html":ele.dataset.showHtml,"message":msg}),
            beforeSend: function(msg){
                talkAll();
            },
            complete: function(data){
                $('#send-chat').prop('disabled', false);
            },
            success: function(response){

                //update the clientId for the next time
                if(response.data.client.id==='' || typeof response.data.client.id === "undefined"){
                    console.error('unable to obtain client.id')
                }else{
                    ele.dataset.clientid =response.data.client.id;
                }

                talkAll();
                setTimeout(function(){

                    console.log('success',response.data.conversation.output,response.data.bot.image)
                    $("#lemur-conversation-inner").generateBotMessage(response.data.conversation.output,response.data.bot.image,response.data.bot.name);
                    talkAll();
                }, 250);
            },
            done: function(data){

            }
        });
    };



})();

function hideAll(){
    $('#animated-bot-avatar-default').hide()
    $('#animated-bot-avatar-talking-1').hide()
    $('#animated-bot-avatar-talking-2').hide()
    $('#animated-bot-avatar-talking-3').hide()
    $('#animated-bot-avatar-waiting-1').hide()
    $('#animated-bot-avatar-waiting-2').hide()
}

function talkAll(){
    if(!$('.talking-head').is(":visible")){
        $('#animated-bot-avatar-default').show()
    }else{
        hideAll();
        $('#animated-bot-avatar-default').show()
        setTimeout(function(){
            hideAll();
            $('#animated-bot-avatar-talking-1').show()
        }, 250);
        setTimeout(function(){
            hideAll();
            $('#animated-bot-avatar-talking-2').show()
        }, 500);
        setTimeout(function(){
            hideAll();
            $('#animated-bot-avatar-talking-3').show()
        }, 750);
    }
}


function waitingFace(){

    hideAll();
    $('#animated-bot-avatar-default').show()


    setTimeout(function(){
        hideAll();
        $('#animated-bot-avatar-waiting-1').show()
    }, 250);
    setTimeout(function(){
        hideAll();
        $('#animated-bot-avatar-waiting-2').show()
    }, 500);

    setTimeout(waitingFace,5000);
}

$( document ).ready(function() {

    waitingFace();

    (function( $ ){

        $.fn.generateBotMessage = function(msg, image, name){
            let str = "" +
                "<div class='lemur-conversation-item item-left clearfix'> " +
                "<div class='lemur-conversation-user'> " +
                "<img src='"+image+"' class='lemur-conversation-bot-avatar'  alt=''> " +
                "</div> " +
                "<div class='lemur-conversation-body'> " +
                "<div class='lemur-name'>" +
                name +
                "</div> " +
                "<div class='lemur-time hidden-xs'>" +
                new Date().toLocaleString() +
                "</div> " +
                "<div class='lemur-text'>" +
                msg +
                "</div> " +
                "</div> " +
                "</div>"
            $(this).append(str);
            $("#slimScrollDiv").stop().animate({ scrollTop: $("#slimScrollDiv")[0].scrollHeight}, 1000);
        }


        $.fn.generateUserMessage = function(msg, image) {
            let str="";
            str += "<div class='lemur-conversation-item item-right clearfix'>";
            str += "<div class='lemur-conversation-user'>"
            str += "<img src='"+image+"' class='lemur-conversation-user-avatar'  alt=''>"
            str += "</div>"
            str += "<div class='lemur-conversation-body'> " +
                "<div class='lemur-name'>" +
                "You " +
                "</div> " +
                "<div class='lemur-time hidden-xs'>" +
                new Date().toLocaleString() +
                "</div> " +
                "<div class='lemur-text'>" +
                msg +
                "</div> " +
                "</div> " +
                "</div>"

            $(this).append(str);
            $("#slimScrollDiv").stop().animate({ scrollTop: $("#slimScrollDiv")[0].scrollHeight}, 1000);
        };
    })( jQuery );

    (function( $ ){
        $.fn.animateAvatar = function(lemurtarObj) {
            var svg = Avataaars.create(
                lemurtarObj
            );
            $(this).append(svg);
        };
    })( jQuery );




})




