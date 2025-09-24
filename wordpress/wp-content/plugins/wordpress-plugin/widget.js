(function(){
  function el(sel){ return document.querySelector(sel); }
  function div(cls, html){ const d=document.createElement('div'); d.className=cls; d.innerHTML=html; return d; }

  function init(){
    const root = el('#qcv-chatbot .qcv-messages');
    const input = el('#qcv-chatbot #qcv-text');
    const btn = el('#qcv-chatbot #qcv-send');
    if(!root || !input || !btn) return;

    async function send(){
      const text = (input.value || '').trim();
      if(!text) return;
      root.appendChild(div('me', text));
      input.value = '';
      root.appendChild(div('bot', '<em>Đang xử lý...</em>'));
      root.scrollTop = root.scrollHeight;

      try{
        const resp = await fetch(QCV_CFG.API_URL, {
          method:'POST',
          headers:{'Content-Type':'application/json'},
          body: JSON.stringify({ messages: [{role:'user', content:text}] })
        });
        const data = await resp.json();
        const last = root.querySelector('.bot:last-child');
        last.innerHTML = (data && data.content) ? data.content : '<em>Không nhận được phản hồi.</em>';
      }catch(e){
        const last = root.querySelector('.bot:last-child');
        last.innerHTML = '<em>Lỗi kết nối API.</em>';
      }
      root.scrollTop = root.scrollHeight;
    }

    btn.addEventListener('click', send);
    input.addEventListener('keydown', function(e){
      if(e.key === 'Enter'){ e.preventDefault(); send(); }
    });
  }

  if(document.readyState === 'loading'){
    document.addEventListener('DOMContentLoaded', init);
  }else{
    init();
  }
})();