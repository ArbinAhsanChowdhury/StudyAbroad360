// assets/js/push_notifications.js - Web Push Registration, Permission Handler & Extended Ring Alarm Chime

document.addEventListener('DOMContentLoaded', () => {
    initPushNotifications();
});

function initPushNotifications() {
    if ('serviceWorker' in navigator && 'PushManager' in window) {
        navigator.serviceWorker.register('sw.js')
            .then(reg => {
                console.log('✓ Service Worker registered for StudyAbroad360 Reminders');
            })
            .catch(err => {
                console.warn('Service Worker registration skipped or failed:', err);
            });
    }
}

/**
 * Synthesize an extended multi-chime alarm ring sound (~2.6 seconds) using Web Audio API
 */
function playReminderRingChime() {
    try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        const ctx = new AudioCtx();

        if (ctx.state === 'suspended') {
            ctx.resume();
        }

        // 4 consecutive alarm ring chimes spaced over ~2.6 seconds
        const ringTimes = [0.0, 0.6, 1.2, 1.8];

        ringTimes.forEach(startTime => {
            // Tone 1: High Bell Note (D5 - 587.33Hz)
            const osc1 = ctx.createOscillator();
            const gain1 = ctx.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(587.33, ctx.currentTime + startTime);
            gain1.gain.setValueAtTime(0.35, ctx.currentTime + startTime);
            gain1.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + startTime + 0.5);
            osc1.connect(gain1);
            gain1.connect(ctx.destination);
            osc1.start(ctx.currentTime + startTime);
            osc1.stop(ctx.currentTime + startTime + 0.5);

            // Tone 2: Harmonious Ring Note (A5 - 880Hz) 120ms after Tone 1
            const osc2 = ctx.createOscillator();
            const gain2 = ctx.createGain();
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(880, ctx.currentTime + startTime + 0.12);
            gain2.gain.setValueAtTime(0.45, ctx.currentTime + startTime + 0.12);
            gain2.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + startTime + 0.75);
            osc2.connect(gain2);
            gain2.connect(ctx.destination);
            osc2.start(ctx.currentTime + startTime + 0.12);
            osc2.stop(ctx.currentTime + startTime + 0.75);
        });

    } catch (e) {
        console.warn('Web Audio playback failed or blocked by browser policy:', e);
    }
}

function requestNotificationPermission() {
    if (!('Notification' in window)) {
        alert('This browser does not support web push notifications.');
        return;
    }

    Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            playReminderRingChime();
            showToast('Web Push Notifications Enabled! Extended ring chime active.', 'success');
            triggerTestPushAlert('🔔 StudyAbroad360 Reminders Enabled', 'You will now receive automated deadline alerts on this device!');
        } else if (permission === 'denied') {
            showToast('Notification permission was blocked in your browser settings.', 'error');
        }
    });
}

function triggerTestPushAlert(title, message) {
    playReminderRingChime(); // Play the extended ring sound
    if (Notification.permission === 'granted') {
        navigator.serviceWorker.ready.then(registration => {
            registration.showNotification(title, {
                body: message,
                icon: 'https://cdn-icons-png.flaticon.com/512/2991/2991148.png',
                vibrate: [300, 100, 300, 100, 300, 100, 300], // Extended vibration pattern for mobile
                data: { url: 'student_dashboard.php' }
            });
        });
    }
}

function showToast(message, type = 'info') {
    if (type === 'success' || type === 'info') {
        playReminderRingChime();
    }
    const toast = document.createElement('div');
    toast.style.position = 'fixed';
    toast.style.bottom = '24px';
    toast.style.right = '24px';
    toast.style.background = type === 'success' ? '#10b981' : (type === 'error' ? '#ef4444' : '#0284c7');
    toast.style.color = '#ffffff';
    toast.style.padding = '14px 22px';
    toast.style.borderRadius = '10px';
    toast.style.boxShadow = '0 10px 25px rgba(0,0,0,0.15)';
    toast.style.zIndex = '9999';
    toast.style.fontWeight = '600';
    toast.style.fontSize = '0.9rem';
    toast.style.display = 'flex';
    toast.style.alignItems = 'center';
    toast.style.gap = '10px';
    toast.innerHTML = `<i class="${type === 'success' ? 'fas fa-check-circle' : 'fas fa-info-circle'}"></i> ${message}`;
    
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.5s ease';
        setTimeout(() => toast.remove(), 500);
    }, 4500);
}
