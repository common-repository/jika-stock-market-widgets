function waitForElement(e,r=!1){const t=()=>{if(Array.isArray(e))return!e.some((e=>r?document.querySelector(e):!document.querySelector(e)));const t=document.querySelector(e);return r?!t:t};return new Promise((e=>{let r=t();if(r)return e(r);const n=new MutationObserver((o=>{r=t(),r&&(n.disconnect(),e(r))}));n.observe(document.body,{childList:!0,subtree:!0})}))}