Echo.channel('chat')
    .listen('.ChatSendMessage', (e) => {
        let message = e.message;
        console.log("send", message);

        // https://api.betterttv.net/3/cached/emotes/global
        // https://cdn.betterttv.net/emote/{$id}/2x


        const msg = document.createElement("div");
        msg.className = "scroll-left";
        let top = Math.floor(Math.random() * 500);
        msg.style.top = top + "px";
        msg.style.color = e.setting.color;
        msg.innerHTML = message;
        document.body.appendChild(msg);
    });
