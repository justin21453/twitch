Echo.channel('chat')
    .listen('.ChatSendMessage', (e) => {
        console.log("send", e.message);
    });
