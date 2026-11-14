(() => {
 const root=document.querySelector('#gameStage');
 if(!root)return;
 const items=JSON.parse(root.dataset.items||'[]').filter(x=>x.title&&x.description);
 let activeTimer=null;
 const $=s=>root.querySelector(s);
 const all=s=>[...root.querySelectorAll(s)];
 const read=(k,d)=>{try{return JSON.parse(localStorage.getItem(k)??JSON.stringify(d));}catch{return d;}};
 const stats=()=>{const s=read('i01_game_stats',{xp:0,played:0,best:0});document.querySelector('#gameXp').textContent=s.xp;document.querySelector('#gamePlayed').textContent=s.played;document.querySelector('#gameBest').textContent=s.best;const e=document.querySelector('#learningXp');if(e)e.textContent=s.xp+' XP';};
 const award=(points,score,total,correct)=>{
   const s=read('i01_game_stats',{xp:0,played:0,best:0});s.xp+=Math.max(0,points);s.played++;s.best=Math.max(s.best,score);localStorage.setItem('i01_game_stats',JSON.stringify(s));localStorage.setItem('i01_xp',String((Number(localStorage.getItem('i01_xp'))||0)+Math.max(0,points)));stats();
   const h=read('i01_game_history',[]);h.unshift({game:document.querySelector('.game-select.active')?.dataset.game||'game',points,score,at:new Date().toLocaleString('vi-VN')});localStorage.setItem('i01_game_history',JSON.stringify(h.slice(0,50)));
   const csrf=document.querySelector('meta[name="csrf-token"]')?.content;
   if(csrf&&window.I01_ROUTES?.saveScore)fetch(window.I01_ROUTES.saveScore,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:JSON.stringify({score,correct_answers:correct,total_questions:Math.max(1,total)})}).catch(()=>{});
 };
 const shuffle=a=>[...a].sort(()=>Math.random()-.5);
 const safe=s=>String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
 const empty=()=>{root.innerHTML='<div class="game-stage-empty"><span>🎮</span><h2>Chọn một trò chơi để bắt đầu</h2><p>Mỗi trò chơi giúp bạn học từ hồ sơ bảo vật.</p></div>';};
 const finish=(title,score,correct,total,points,detail)=>{
   root.innerHTML=`<div class="game-result"><span class="eyebrow">HOÀN THÀNH TRÒ CHƠI</span><div class="result-medal">🏆</div><h2>${title}</h2><strong class="result-score">${score}</strong><p>${detail}</p><div class="result-xp">+${points} XP đã nhận</div><div class="game-result-actions"><button class="btn btn-dark" id="gameReplay">Chơi lại</button><button class="btn btn-outline-dark" id="gameBack">Chọn trò khác</button></div></div>`;
   award(points,score,total,correct);
   $('#gameReplay').onclick=()=>start(document.querySelector('.game-select.active')?.dataset.game||'memory');
   $('#gameBack').onclick=empty;
 };
 function memory(){
   const chosen=shuffle(items).slice(0,6);
   let cards=shuffle(chosen.flatMap((x,i)=>[{key:x.id,kind:'name',content:x.title,mark:i},{key:x.id,kind:'fact',content:(x.description||'').slice(0,115)+'…',mark:i}]));
   let opened=[],matched=new Set(),moves=0,lock=false;
   root.innerHTML=`<div class="game-bar"><div><small>GAME 01 · GHÉP CẶP</small><h2>Tìm đúng cặp tương ứng</h2></div><div class="game-counter">Lượt lật <b id="moves">0</b> · Đã ghép <b id="pairs">0/6</b></div></div><p class="game-instruction">Ghép tên bảo vật với đoạn mô tả thuộc về chính hiện vật đó. Chạm hai thẻ để lật.</p><div class="memory-grid" id="memoryGrid">${cards.map((c,i)=>`<button class="memory-card" data-index="${i}" aria-label="Lật thẻ"><span class="memory-hidden">?</span><span class="memory-content">${c.kind==='name'?'<b>'+safe(c.content)+'</b>':'<small>'+safe(c.content)+'</small>'}</span></button>`).join('')}</div><div class="game-bottom"><span id="memoryFeedback">Mỗi cặp đúng giúp bạn hiểu sâu hơn về một hồ sơ.</span><button class="btn btn-outline-dark" id="memoryReset">Xáo lại</button></div>`;
   all('.memory-card').forEach(btn=>btn.addEventListener('click',()=>{
     const idx=Number(btn.dataset.index),c=cards[idx];
     if(lock||matched.has(c.key)||opened.includes(idx))return;
     btn.classList.add('revealed');opened.push(idx);
     if(opened.length===2){moves++;$('#moves').textContent=moves;const [a,b]=opened.map(i=>cards[i]);
       if(a.key===b.key&&a.kind!==b.kind){matched.add(a.key);opened.forEach(i=>root.querySelector(`[data-index="${i}"]`).classList.add('matched'));$('#pairs').textContent=matched.size+'/6';$('#memoryFeedback').textContent='Chính xác! '+a.content+' — hãy đọc kỹ mô tả trong hồ sơ.';opened=[];if(matched.size===6){const pts=Math.max(20,100-(moves-6)*5);setTimeout(()=>finish('Bạn đã hoàn thành ghép cặp!',Math.max(0,100-(moves-6)*5),6,6,pts,`Bạn ghép đủ 6 cặp sau ${moves} lượt lật.`),450);}}
       else{lock=true;$('#memoryFeedback').textContent='Chưa khớp. Hãy quan sát kỹ tên gọi và nội dung mô tả.';setTimeout(()=>{opened.forEach(i=>root.querySelector(`[data-index="${i}"]`).classList.remove('revealed'));opened=[];lock=false;},850);}
     }
   }));
   $('#memoryReset').onclick=memory;
 }
 function detective(){
   const rounds=shuffle(items.filter(x=>x.description)).slice(0,5);let n=0,correct=0,locked=false;
   const render=()=>{
     const x=rounds[n];locked=false;
     const opts=shuffle([x,...shuffle(items.filter(y=>y.id!==x.id)).slice(0,3)]);
     root.innerHTML=`<div class="game-bar"><div><small>GAME 02 · THÁM TỬ DI SẢN</small><h2>Đọc manh mối, tìm hiện vật</h2></div><div class="game-counter">Manh mối <b>${n+1}/5</b></div></div><div class="detective-clue"><span class="eyebrow">HỒ SƠ MẬT · MANH MỐI</span><p>${safe(x.description)}</p><small>Hãy dựa vào chi tiết trong mô tả để xác định đúng hiện vật.</small></div><div class="detective-options">${opts.map((o,i)=>`<button class="answer-option" data-id="${safe(o.id)}"><span>${String.fromCharCode(65+i)}</span>${safe(o.title)}</button>`).join('')}</div><div class="answer-feedback" id="detectiveFeedback" hidden></div><div class="game-bottom"><span>Trả lời đúng để mở khóa manh mối tiếp theo.</span><button class="btn btn-dark" id="detectiveNext" disabled>${n===4?'Xem kết quả':'Tiếp tục →'}</button></div>`;
     all('.answer-option').forEach(b=>b.onclick=()=>{
       if(locked)return;locked=true;const ok=b.dataset.id===x.id;if(ok)correct++;
       all('.answer-option').forEach(o=>{o.disabled=true;if(o.dataset.id===x.id)o.classList.add('correct');else if(o===b)o.classList.add('wrong');});
       const f=$('#detectiveFeedback');f.hidden=false;f.innerHTML=`<b>${ok?'Bạn đã tìm đúng hiện vật!':'Chưa chính xác.'}</b><p>${safe(x.title)} — ${safe(x.date||'Niên đại chưa xác định trong hồ sơ')}.</p><a href="/bao-vat/${encodeURIComponent(x.id)}" target="_blank">Đọc hồ sơ đầy đủ ↗</a>`;
       $('#detectiveNext').disabled=false;
     });
     $('#detectiveNext').onclick=()=>{if(!locked)return;if(n===4)finish('Hoàn thành hành trình thám tử',correct*20,correct,5,correct*15,`Bạn tìm đúng ${correct}/5 bảo vật.`);else{n++;render();}};
   };render();
 }
 function rush(){
   let time=60,correct=0,answered=0,streak=0,bestStreak=0,active=true,current=null,timer=null;
   const render=()=>{
     current=shuffle(items.filter(x=>x.question&&x.options?.length>=4))[0];
     const options=shuffle(current.options.map((v,i)=>({v,i})));
     root.innerHTML=`<div class="game-bar"><div><small>GAME 03 · TỐC ĐỘ</small><h2>60 giây thử tài</h2></div><div class="rush-clock" id="rushClock">${time}s</div></div><div class="rush-progress"><i style="width:${time/60*100}%"></i></div><div class="rush-question"><span class="eyebrow">CÂU HỎI NHANH</span><h3>${safe(current.question)}</h3><div class="answer-list">${options.map(o=>`<button class="answer-option" data-i="${o.i}"><span>${String.fromCharCode(65+o.i)}</span>${safe(o.v)}</button>`).join('')}</div></div><div class="game-counter">Đúng <b>${correct}</b> · Đã trả lời <b>${answered}</b> · Chuỗi tốt nhất <b>${bestStreak}</b></div>`;
     all('.answer-option').forEach(b=>b.onclick=()=>{
       if(!active||b.disabled)return;answered++;const ok=Number(b.dataset.i)===Number(current.answer);if(ok){correct++;streak++;bestStreak=Math.max(streak,bestStreak);}else streak=0;
       all('.answer-option').forEach(o=>{o.disabled=true;if(Number(o.dataset.i)===Number(current.answer))o.classList.add('correct');else if(o===b)o.classList.add('wrong');});
       const info=document.createElement('p');info.className='rush-explanation';info.textContent=current.explanation||'Hãy mở hồ sơ để đọc thêm thông tin.';root.querySelector('.rush-question').append(info);
       setTimeout(()=>{if(active)render();},650);
     });
   };
   render();
   timer=setInterval(()=>{time--;const clock=$('#rushClock');if(clock)clock.textContent=time+'s';const bar=root.querySelector('.rush-progress i');if(bar)bar.style.width=(time/60*100)+'%';if(time<=0){active=false;clearInterval(timer);activeTimer=null;const pct=Math.round(correct/Math.max(1,answered)*100);const pts=correct*10+bestStreak*2;finish('Hết giờ! Bạn đã hoàn thành',pct,correct,Math.max(1,answered),pts,`Bạn trả lời đúng ${correct}/${answered} câu. Chuỗi đúng liên tiếp tốt nhất: ${bestStreak}.`);}},1000);activeTimer=timer;
 }
 function start(which){if(activeTimer){clearInterval(activeTimer);activeTimer=null;}document.querySelectorAll('.game-select').forEach(b=>b.classList.toggle('active',b.dataset.game===which));if(which==='memory')memory();if(which==='detective')detective();if(which==='rush')rush();}
 document.querySelectorAll('.game-select').forEach(b=>b.addEventListener('click',()=>start(b.dataset.game)));
 stats();
})();