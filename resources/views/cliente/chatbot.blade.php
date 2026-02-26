@extends('layouts.app')
@section('title', 'Chatbot IA - VEXIS')
@section('content')
<div class="vx-page-header"><h1 class="vx-page-title"><i class="bi bi-robot" style="color:var(--vx-primary);"></i> Asistente Virtual</h1><a href="{{ route('cliente.inicio') }}" class="vx-btn vx-btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>

<div style="max-width:800px;margin:0 auto;">
    <div class="vx-card" style="height:520px;display:flex;flex-direction:column;">
        <div class="vx-card-header" style="flex-shrink:0;display:flex;align-items:center;gap:10px;">
            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--vx-primary),#2980b9);display:flex;align-items:center;justify-content:center;color:white;font-size:18px;"><i class="bi bi-robot"></i></div>
            <div><h4 style="margin:0;font-size:14px;">Asistente Grupo ARI</h4><p style="margin:0;font-size:11px;color:var(--vx-text-muted);">Impulsado por Gemini AI — Pregúntame sobre vehículos, precios y disponibilidad</p></div>
            <span id="statusDot" style="margin-left:auto;width:8px;height:8px;border-radius:50%;background:#2ecc71;"></span>
        </div>
        <div id="chatMessages" class="vx-card-body" style="flex:1;overflow-y:auto;padding:16px 20px;display:flex;flex-direction:column;gap:12px;">
            <div class="chat-msg chat-bot">
                <div class="chat-avatar"><i class="bi bi-robot"></i></div>
                <div class="chat-bubble">¡Hola! 👋 Soy el asistente virtual de <strong>Grupo ARI</strong>. Puedo ayudarte con información sobre nuestros vehículos Nissan, Renault y Dacia, precios, stock disponible y concesionarios en Canarias. ¿En qué puedo ayudarte?</div>
            </div>
        </div>
        <div style="flex-shrink:0;padding:12px 16px;border-top:1px solid var(--vx-border);display:flex;gap:8px;">
            <input type="text" id="chatInput" class="vx-input" placeholder="Escribe tu pregunta..." style="flex:1;" autocomplete="off">
            <button id="chatSend" class="vx-btn vx-btn-primary" style="padding:8px 16px;"><i class="bi bi-send"></i></button>
        </div>
    </div>
    <div style="display:flex;gap:8px;margin-top:12px;flex-wrap:wrap;">
        <button class="chat-suggestion" onclick="sendSuggestion(this)">¿Qué modelos Nissan tenéis?</button>
        <button class="chat-suggestion" onclick="sendSuggestion(this)">Precio del Dacia Duster</button>
        <button class="chat-suggestion" onclick="sendSuggestion(this)">¿Dónde estáis en Gran Canaria?</button>
        <button class="chat-suggestion" onclick="sendSuggestion(this)">Coches eléctricos disponibles</button>
    </div>
</div>

@push('styles')
<style>
.chat-msg{display:flex;gap:10px;max-width:85%;}
.chat-bot{align-self:flex-start;}
.chat-user{align-self:flex-end;flex-direction:row-reverse;}
.chat-avatar{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px;}
.chat-bot .chat-avatar{background:linear-gradient(135deg,var(--vx-primary),#2980b9);color:white;}
.chat-user .chat-avatar{background:var(--vx-gray-200);color:var(--vx-text);}
.chat-bubble{padding:10px 14px;border-radius:12px;font-size:13px;line-height:1.5;word-wrap:break-word;}
.chat-bot .chat-bubble{background:var(--vx-bg);border:1px solid var(--vx-border);border-top-left-radius:4px;}
.chat-user .chat-bubble{background:var(--vx-primary);color:white;border-top-right-radius:4px;}
.chat-typing{display:flex;gap:4px;padding:10px 14px;}
.chat-typing span{width:7px;height:7px;border-radius:50%;background:var(--vx-text-muted);animation:bounce 1.4s infinite;}
.chat-typing span:nth-child(2){animation-delay:0.2s;}
.chat-typing span:nth-child(3){animation-delay:0.4s;}
@keyframes bounce{0%,80%,100%{transform:translateY(0);}40%{transform:translateY(-8px);}}
.chat-suggestion{padding:6px 12px;border:1px solid var(--vx-border);border-radius:20px;background:var(--vx-surface);color:var(--vx-text-muted);font-size:12px;cursor:pointer;transition:all 0.2s;}
.chat-suggestion:hover{border-color:var(--vx-primary);color:var(--vx-primary);background:rgba(51,170,221,0.05);}
</style>
@endpush

@push('scripts')
<script>
const chatMessages = document.getElementById('chatMessages');
const chatInput = document.getElementById('chatInput');
const chatSend = document.getElementById('chatSend');

function addMessage(text, isUser = false) {
    const div = document.createElement('div');
    div.className = `chat-msg ${isUser ? 'chat-user' : 'chat-bot'}`;
    const icon = isUser ? 'bi-person' : 'bi-robot';
    // Convert markdown-like bold **text** to <strong>text</strong>
    const formatted = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>');
    div.innerHTML = `<div class="chat-avatar"><i class="bi ${icon}"></i></div><div class="chat-bubble">${formatted}</div>`;
    chatMessages.appendChild(div);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function addTyping() {
    const div = document.createElement('div');
    div.className = 'chat-msg chat-bot';
    div.id = 'typing';
    div.innerHTML = '<div class="chat-avatar"><i class="bi bi-robot"></i></div><div class="chat-bubble chat-typing"><span></span><span></span><span></span></div>';
    chatMessages.appendChild(div);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function removeTyping() { document.getElementById('typing')?.remove(); }

async function sendMessage(text) {
    if (!text.trim()) return;
    addMessage(text, true);
    chatInput.value = '';
    chatInput.disabled = true;
    chatSend.disabled = true;
    addTyping();

    try {
        const res = await fetch('{{ route("cliente.chatbot.query") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ mensaje: text })
        });
        const data = await res.json();
        removeTyping();
        addMessage(data.respuesta || 'Sin respuesta.');
    } catch (e) {
        removeTyping();
        addMessage('Error de conexión. Inténtalo de nuevo.');
    }
    chatInput.disabled = false;
    chatSend.disabled = false;
    chatInput.focus();
}

chatSend.addEventListener('click', () => sendMessage(chatInput.value));
chatInput.addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(chatInput.value); });
function sendSuggestion(btn) { sendMessage(btn.textContent); }
</script>
@endpush
@endsection
