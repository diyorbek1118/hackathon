// Forecast Page JavaScript
let currentForecastPeriod = 30;
let forecastChart;

// Forecast ma'lumotlarini yuklash
async function loadForecastData(days) {
    try {
        showLoading();
        // XATO 1: Template literal syntax to'g'rilanishi kerak
        const response = await fetch(`/pages/chartData?days=${days}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!response.ok) {
            throw new Error('Ma\'lumot yuklashda xatolik');
        }

        const data = await response.json();
        console.log('Loaded forecast data:', data);

        // XATO 2: forecastData global o'zgaruvchiga yangilash
        if (data && data.labels) {
            forecastData[days] = data;
        }

        updateUI();
        hideLoading();
        return data;
    } catch (error) {
        console.error('Error loading forecast:', error);
        hideLoading();
        showError('Ma\'lumot yuklashda xatolik yuz berdi. Iltimos qaytadan urinib ko\'ring.');
        return null;
    }
}

// XATO 3: Bu yerda console.log o'chirilishi kerak (funksiyani chaqirmaslik kerak)
// console.log(loadForecastData(10)); - BU XATO!

// Sample forecast data
const forecastData = {
    30: {
        labels: Array.from({ length: 30 }, (_, i) => `${i + 1}-kun`),
        actual: [10.5, 10.8, 10.2, 11.1, 10.9, 11.5, 11.2, 10.8, 11.4, 11.0, 10.7, 11.3, 10.9, 11.6, 11.1],
        predicted: [11.2, 10.9, 11.5, 11.0, 10.6, 11.3, 10.8, 11.4, 10.7, 11.1, 10.9, 11.5, 11.2, 10.8, 11.4, 11.0, 10.7, 11.3, 10.9, 11.6, 11.2, 10.8, 11.4, 11.1, 10.7, 11.3, 10.9, 11.5, 11.2, 10.8],
        upperBound: [11.8, 11.5, 12.1, 11.6, 11.2, 11.9, 11.4, 12.0, 11.3, 11.7, 11.5, 12.1, 11.8, 11.4, 12.0, 11.6, 11.3, 11.9, 11.5, 12.2, 11.8, 11.4, 12.0, 11.7, 11.3, 11.9, 11.5, 12.1, 11.8, 11.4],
        lowerBound: [10.6, 10.3, 10.9, 10.4, 10.0, 10.7, 10.2, 10.8, 10.1, 10.5, 10.3, 10.9, 10.6, 10.2, 10.8, 10.4, 10.1, 10.7, 10.3, 11.0, 10.6, 10.2, 10.8, 10.5, 10.1, 10.7, 10.3, 10.9, 10.6, 10.2]
    },
    60: {
        labels: Array.from({ length: 60 }, (_, i) => `${i + 1}-kun`),
        actual: [10.5, 10.8, 10.2, 11.1, 10.9, 11.5, 11.2, 10.8, 11.4, 11.0, 10.7, 11.3, 10.9, 11.6, 11.1],
        predicted: Array.from({ length: 60 }, (_, i) => 10.5 + Math.sin(i / 5) * 1.2 + (i * 0.02)),
        upperBound: Array.from({ length: 60 }, (_, i) => 11.1 + Math.sin(i / 5) * 1.2 + (i * 0.02)),
        lowerBound: Array.from({ length: 60 }, (_, i) => 9.9 + Math.sin(i / 5) * 1.2 + (i * 0.02))
    },
    90: {
        labels: Array.from({ length: 90 }, (_, i) => `${i + 1}-kun`),
        actual: [10.5, 10.8, 10.2, 11.1, 10.9, 11.5, 11.2, 10.8, 11.4, 11.0, 10.7, 11.3, 10.9, 11.6, 11.1],
        predicted: Array.from({ length: 90 }, (_, i) => 10.5 + Math.sin(i / 7) * 1.5 + (i * 0.015)),
        upperBound: Array.from({ length: 90 }, (_, i) => 11.2 + Math.sin(i / 7) * 1.5 + (i * 0.015)),
        lowerBound: Array.from({ length: 90 }, (_, i) => 9.8 + Math.sin(i / 7) * 1.5 + (i * 0.015))
    }
};

// Initialize forecast chart
function initForecastChart() {
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#e2e8f0' : '#1e293b';
    const gridColor = isDark ? '#334155' : '#e2e8f0';
    const ctx = document.getElementById('forecastChart');

    // XATO 4: Canvas elementini tekshirish
    if (!ctx) {
        console.error('forecastChart elementi topilmadi');
        return;
    }

    const data = forecastData[currentForecastPeriod];

    forecastChart = new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Haqiqiy ma\'lumot',
                    data: data.actual.concat(Array(data.predicted.length - data.actual.length).fill(null)),
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4
                },
                {
                    label: 'Prognoz',
                    data: Array(data.actual.length).fill(null).concat(data.predicted.slice(data.actual.length)),
                    borderColor: 'rgba(34, 197, 94, 1)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 3,
                    borderDash: [5, 5],
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    tension: 0.4
                },
                {
                    label: 'Yuqori chegara',
                    data: Array(data.actual.length).fill(null).concat(data.upperBound.slice(data.actual.length)),
                    borderColor: 'rgba(239, 68, 68, 0.3)',
                    backgroundColor: 'rgba(239, 68, 68, 0.05)',
                    borderWidth: 1,
                    borderDash: [2, 2],
                    pointRadius: 0,
                    fill: '+1',
                    tension: 0.4
                },
                {
                    label: 'Quyi chegara',
                    data: Array(data.actual.length).fill(null).concat(data.lowerBound.slice(data.actual.length)),
                    borderColor: 'rgba(239, 68, 68, 0.3)',
                    backgroundColor: 'rgba(239, 68, 68, 0.05)',
                    borderWidth: 1,
                    borderDash: [2, 2],
                    pointRadius: 0,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    labels: {
                        color: textColor,
                        font: {
                            size: 12,
                            weight: 'bold'
                        },
                        usePointStyle: true,
                        padding: 15
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            if (context.parsed.y !== null) {
                                return context.dataset.label + ': ' + context.parsed.y.toFixed(1) + 'M so\'m';
                            }
                            return '';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        color: textColor,
                        callback: function (value) {
                            return value.toFixed(1) + 'M';
                        }
                    },
                    grid: {
                        color: gridColor
                    }
                },
                x: {
                    ticks: {
                        color: textColor,
                        maxTicksLimit: 15
                    },
                    grid: {
                        color: gridColor
                    }
                }
            }
        }
    });
}

// Update forecast period
function updateForecastPeriod(days) {
    currentForecastPeriod = days;

    // Update button styles
    [30, 60, 90].forEach(d => {
        const btn = document.getElementById('btn-' + d);
        if (btn) {
            if (d === days) {
                btn.classList.remove('bg-slate-200', 'dark:bg-slate-800', 'text-slate-700', 'dark:text-slate-300');
                btn.classList.add('bg-blue-500', 'text-white');
            } else {
                btn.classList.remove('bg-blue-500', 'text-white');
                btn.classList.add('bg-slate-200', 'dark:bg-slate-800', 'text-slate-700', 'dark:text-slate-300');
            }
        }
    });

    // Update chart
    updateForecastChart();
}

// Update forecast chart with new data
function updateForecastChart() {
    if (!forecastChart) {
        console.error('Chart mavjud emas');
        return;
    }

    const data = forecastData[currentForecastPeriod];

    forecastChart.data.labels = data.labels;
    forecastChart.data.datasets[0].data = data.actual.concat(Array(data.predicted.length - data.actual.length).fill(null));
    forecastChart.data.datasets[1].data = Array(data.actual.length).fill(null).concat(data.predicted.slice(data.actual.length));
    forecastChart.data.datasets[2].data = Array(data.actual.length).fill(null).concat(data.upperBound.slice(data.actual.length));
    forecastChart.data.datasets[3].data = Array(data.actual.length).fill(null).concat(data.lowerBound.slice(data.actual.length));

    forecastChart.update();
}

// AI Chat functionality
const aiResponses = {
    'xarajat': 'Xarajatlarni kamaytirish uchun: 1) Operatsion xarajatlarni tahlil qiling, 2) Keraksiz xarajatlarni aniqlang, 3) Sotuvchilar bilan qayta muzokaralar olib boring, 4) Jarayonlarni avtomatlashtiring.',
    'qarz': 'Qarzlarni to\'lash strategiyasi: Birinchi navbatda yuqori foizli qarzlarni to\'lang (avalanche usuli) yoki kichik qarzlarni birinchi to\'lang (snowball usuli). Sizning holatda yuqori foizli qarzlarni birinchi to\'lash tavsiya etiladi.',
    'investitsiya': 'Investitsiya qilish uchun eng yaxshi vaqt - pul oqimi barqaror va foyda marjasi yuqori bo\'lganda. Hozirgi prognozga ko\'ra, 3 oy ichida investitsiya qilish maqsadga muvofiq.',
    'default': 'Savolingiz uchun rahmat! Men sizga moliyaviy maslahat berishga tayyorman. Iltimos, aniqroq savol bering yoki yuqoridagi tezkor tugmalardan foydalaning.'
};

async function sendMessage() {
    const input = document.getElementById('chat-input');
    const message = input.value.trim();

    if (!message) return;

    // Foydalanuvchi xabarini UI ga qo'shamiz
    addMessage(message, 'user');
    input.value = '';

    // XATO 5: Loading indikatorini ko'rsatish
    addTypingIndicator();

    try {
        // XATO 6: Template literal to'g'rilanishi kerak
        const response = await fetch(`/pages/chartData?days=${currentForecastPeriod}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        });

        if (!response.ok) {
            throw new Error("Ma'lumot yuklashda xatolik");
        }

        const chartData = await response.json();
        console.log("Forecast data:", chartData);

        // HuggingFace API chaqiramiz
        const aiResponse = await fetch("https://router.huggingface.co/v1/chat/completions", {
            method: "POST",
            headers: {
                "Authorization": import.meta.env.VITE_HF_TOKEN,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                model: "openai/gpt-oss-120b:fastest",
                temperature: 0.3,
                max_tokens: 1200,
                messages: [
                    {
                        role: "system",
                        content: "Siz moliyaviy prognoz yordamchisisiz. Berilgan chartData va foydalanuvchi savolini birgalikda tahlil qiling. O'zbek tilida batafsil javob bering, risklar va tavsiyalarni kiriting."
                    },
                    {
                        role: "user",
                        content: `Savol: ${message}\n\nMa'lumot: ${JSON.stringify(chartData)}`
                    }
                ]
            })
        });

        removeTypingIndicator();

        if (!aiResponse.ok) {
            const errorData = await aiResponse.json().catch(() => ({}));
            console.error("AI xatosi:", errorData);
            throw new Error("AI javob olishda xatolik");
        }

        const data = await aiResponse.json();
        console.log("AI javobi:", data);

        // XATO 7: Response strukturasini tekshirish
        if (data.choices && data.choices.length > 0 && data.choices[0].message) {
            const aiText = data.choices[0].message.content;
            addMessage(aiText, 'ai');
        } else {
            throw new Error("AI javob formati noto'g'ri");
        }

    } catch (error) {
        removeTypingIndicator();
        console.error("Xatolik:", error);
        addMessage("Kechirasiz, AI javob olishda xatolik yuz berdi. Iltimos, qaytadan urinib ko'ring.", 'ai');
    }
}

function quickQuestion(question) {
    const input = document.getElementById('chat-input');
    if (input) {
        input.value = question;
        sendMessage();
    }
}

function getAIResponse(message) {
    const lowerMessage = message.toLowerCase();

    if (lowerMessage.includes('xarajat') || lowerMessage.includes('kamaytir')) {
        return aiResponses.xarajat;
    } else if (lowerMessage.includes('qarz') || lowerMessage.includes('to\'la')) {
        return aiResponses.qarz;
    } else if (lowerMessage.includes('investitsiya') || lowerMessage.includes('sarmoya')) {
        return aiResponses.investitsiya;
    } else {
        return aiResponses.default;
    }
}

// XATO 8: Typing indikator funksiyalari qo'shildi
function addTypingIndicator() {
    const messagesContainer = document.getElementById('chat-messages');
    const typingDiv = document.createElement('div');
    typingDiv.className = 'flex gap-3 typing-indicator';
    typingDiv.innerHTML = `
        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
            </svg>
        </div>
        <div class="flex-1 bg-slate-100 dark:bg-slate-800 rounded-2xl rounded-tl-none p-4">
            <div class="flex gap-1">
                <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
            </div>
        </div>
    `;
    messagesContainer.appendChild(typingDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function removeTypingIndicator() {
    const indicator = document.querySelector('.typing-indicator');
    if (indicator) {
        indicator.remove();
    }
}

function addMessage(text, sender) {
    const messagesContainer = document.getElementById('chat-messages');
    if (!messagesContainer) {
        console.error('chat-messages elementi topilmadi');
        return;
    }

    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex gap-3';

    // XATO 9: marked kutubxonasini tekshirish
    let htmlContent = text;
    if (typeof marked !== 'undefined' && marked.parse) {
        try {
            htmlContent = marked.parse(text);
        } catch (e) {
            console.error('Markdown parse xatosi:', e);
            htmlContent = text.replace(/\n/g, '<br>');
        }
    } else {
        // Markdown mavjud bo'lmasa, oddiy formatlanishi
        htmlContent = text.replace(/\n/g, '<br>');
    }

    if (sender === 'ai') {
        messageDiv.innerHTML = `
            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                </svg>
            </div>
            <div class="flex-1 bg-slate-100 dark:bg-slate-800 rounded-2xl rounded-tl-none p-4 prose prose-sm dark:prose-invert max-w-none">
                ${htmlContent}
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="flex-1"></div>
            <div class="bg-blue-500 text-white rounded-2xl rounded-tr-none p-4 max-w-[80%]">
                ${htmlContent}
            </div>
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                </svg>
            </div>
        `;
    }

    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Helper funksiyalar (agar mavjud bo'lmasa)
function showLoading() {
    const loader = document.getElementById('loading-spinner');
    if (loader) loader.classList.remove('hidden');
}

function hideLoading() {
    const loader = document.getElementById('loading-spinner');
    if (loader) loader.classList.add('hidden');
}

function showError(message) {
    console.error(message);
    // Toast yoki alert ko'rsatish mumkin
}

function updateUI() {
    // UI yangilanishi kerak bo'lsa
    console.log('UI yangilandi');
}

// Enter key to send message
document.addEventListener('DOMContentLoaded', function () {
    const chatInput = document.getElementById('chat-input');
    if (chatInput) {
        chatInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }

    // Initialize chart
    initForecastChart();

    // Watch for theme changes
    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.attributeName === 'class') {
                if (forecastChart) {
                    forecastChart.destroy();
                    initForecastChart();
                }
            }
        });
    });

    observer.observe(document.documentElement, {
        attributes: true
    });
});