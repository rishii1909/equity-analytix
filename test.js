var conn = new WebSocket('ws://localhost:8080/echo');
console.log(conn)
conn.onmessage = function(e) { console.log(e.data); };
setTimeout(() => {
    conn.send('Hello!'); 
}, 200);
