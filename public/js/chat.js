Echo.channel('chat')
    .listen('.ChatSendMessage', (e) => {
        console.log("send", e.message);
        const msg = document.createElement("div");
        msg.className = "scroll-left";
        let top = Math.floor(Math.random() * 500);
        msg.style.top = top + "px";
        msg.innerText = e.message;
        document.body.appendChild(msg);
    });
