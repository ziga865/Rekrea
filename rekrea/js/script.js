document.querySelectorAll('[data-confirm]').forEach(b=>b.addEventListener('click',e=>{if(!confirm(b.dataset.confirm))e.preventDefault();}));
const d=document.getElementById('datum');if(d)d.min=new Date().toISOString().split('T')[0];
