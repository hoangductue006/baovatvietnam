(() => {
 const $ = s => document.querySelector(s);
 const readScores = () => { try { return JSON.parse(localStorage.getItem('i01_scores') || '[]'); } catch { return []; } };
 const saveScore = (score, correct, total) => {
   const a=readScores(); a.unshift({score,correct,at:new Date().toLocaleString('vi-VN')}); localStorage.setItem('i01_scores',JSON.stringify(a.slice(0,50)));
   fetch(window.I01_ROUTES.saveScore,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify({score,correct_answers:correct,total_questions:total})}).catch(()=>{});
 };
 const quiz = $('#quizApp');
 if (quiz) {
   const items=JSON.parse(quiz.dataset.items), pool=items.map(x=>({...x})).sort(()=>Math.random()-.5).slice(0,10);
   let index=0,correct=0,locked=false;
   const render=()=>{
     const item=pool[index]; locked=false;
     $('#questionCounter').textContent=`CÂU ${String(index+1).padStart(2,'0')} / ${pool.length}`;
     $('#progressLabel').textContent=`${Math.round(index/pool.length*100)}% hoàn thành`;
     $('#progressBar').style.width=`${index/pool.length*100}%`;
     $('#questionText').textContent=item.question;
     const qImage=$('.quiz-image');
     if(item.image){qImage.innerHTML=`<img src="${item.image}" alt="${item.title}"><small>${item.image_caption||item.title}</small>`;}
     else {qImage.innerHTML=`<span>ẢNH BẢO VẬT</span><small>Chưa có ảnh được cấp phép · ${item.title}</small>`;}
     $('#answerList').innerHTML=item.options.map((o,i)=>`<button class="answer-option" data-i="${i}"><span>${String.fromCharCode(65+i)}</span>${o}</button>`).join('');
     $('#feedback').hidden=true; $('#nextQuestion').disabled=true;
     $('#answerList').querySelectorAll('button').forEach(b=>b.addEventListener('click',()=>choose(Number(b.dataset.i))));
   };
   const choose=n=>{
     if(locked)return; locked=true; const item=pool[index],ok=n===item.answer; if(ok)correct++;
     $('#answerList').querySelectorAll('button').forEach((b,i)=>{b.disabled=true;if(i===item.answer)b.classList.add('correct');else if(i===n)b.classList.add('wrong');});
     const f=$('#feedback'); f.hidden=false; f.innerHTML=`<b>${ok?'Chính xác!':'Chưa chính xác.'}</b><p>${item.explanation}</p><a href="${item.source}" target="_blank" rel="noopener">Đọc nguồn tham khảo ↗</a>`;
     $('#nextQuestion').disabled=false; $('#nextQuestion').textContent=index===pool.length-1?'Xem kết quả':'Câu tiếp theo →';
   };
   const finish=()=>{ $('#quizApp .quiz-card').hidden=true; $('#quizApp .quiz-top').hidden=true; $('.progress').hidden=true; $('#quizResult').hidden=false; const score=Math.round(correct/pool.length*100); $('#finalScore').textContent=`${correct}/${pool.length} câu đúng · ${score} điểm`; $('#finalMessage').textContent=score>=80?'Tuyệt vời! Bạn đã có kiến thức rất tốt về bộ sưu tập.':'Hãy xem lại thẻ ghi nhớ và thử sức thêm một lần nữa nhé.'; saveScore(score,correct,pool.length); };
   const reset=()=>{index=0;correct=0;pool.sort(()=>Math.random()-.5);$('#quizResult').hidden=true;$('#quizApp .quiz-card').hidden=false;$('#quizApp .quiz-top').hidden=false;$('.progress').hidden=false;render();};
   $('#nextQuestion').addEventListener('click',()=>{if(!locked)return;if(index===pool.length-1)finish();else{index++;render();}});
   $('#restartQuiz').addEventListener('click',reset);$('#playAgain').addEventListener('click',reset);render();
 }
 const flash=$('#flashApp');
 if(flash){
   const items=JSON.parse(flash.dataset.items);let i=0,flipped=false;
   const render=()=>{const x=items[i];$('#flashCounter').textContent=`${String(i+1).padStart(2,'0')} / ${items.length}`;$('#flashTitle').textContent=x.title;$('#flashPeriod').textContent=x.date;$('#flashBackTitle').textContent=x.title;$('#flashDescription').textContent=x.description;$('#flashLocation').textContent=x.holding_current;flipped=false;$('#flashCard').classList.remove('flipped');};
   const flip=()=>{flipped=!flipped;$('#flashCard').classList.toggle('flipped',flipped);};
   $('#flashCard').addEventListener('click',flip);$('#flashFlip').addEventListener('click',flip);
   $('#flashPrev').addEventListener('click',()=>{i=(i-1+items.length)%items.length;render();});$('#flashNext').addEventListener('click',()=>{i=(i+1)%items.length;render();});render();
 }


 // Gemini-powered learning assistant: API key remains on the Laravel server.
 document.addEventListener('click', async e=>{
   const btn=e.target.closest('[data-ai-explain]'); if(!btn)return;
   const box=btn.closest('[data-item-id]'),input=box?.querySelector('[data-ai-question]'),out=box?.querySelector('[data-ai-answer]');
   if(!input||!out)return;
   btn.disabled=true;out.textContent='Đang xử lý câu hỏi...';
   try{
     const res=await fetch(window.I01_ROUTES.explain,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify({id:box.dataset.itemId,question:input.value})});
     const data=await res.json();out.textContent=data.answer||data.message||'Không có phản hồi.';
   }catch{out.textContent='Không thể kết nối. Hãy thử lại.';}
   finally{btn.disabled=false;}
 });
})();
