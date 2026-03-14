(function() {
    const initPusher = () => {
        if (typeof Pusher === 'undefined') {
            setTimeout(initPusher, 500);
            return;
        }

        const pusher = new Pusher('0ul7nxdu46lnlumxgmyy', {
            wsHost: window.location.hostname,
            wsPort: 8090,
            forceTLS: false,
            enabledTransports: ['ws', 'wss'],
            cluster: 'mt1'
        });

        const channel = pusher.subscribe('leads');

        channel.bind('new-lead', function(data) {
            const payload = data.leadData ? data.leadData : data;

            const message = `🚀 Новый лид:<br>
<b>Имя:</b> ${payload.name}<br>
<b>Телефон:</b> ${payload.phone}<br>
<b>Email:</b> ${payload.email}`;

            const leadsUrl = '/admin/resource/lead-resource/lead-index-page';

            const toast = MoonShine.ui.toast(message, 'success');

            if (toast) {
                document.addEventListener('click', (e) => {
                    const toastElement = e.target.closest('.toast');

                    if (toastElement) {
                        window.location.href = leadsUrl;
                    }
                });
            }

            // Звук уведомления
            new Audio('/sounds/notification.mp3').play().catch(e => console.log('Нужен клик по странице для звука'));

            // Обновляем таблицу лидов, если на странице списка
            window.dispatchEvent(new CustomEvent("async-reload"));
        });

        pusher.connection.bind('connected', () => console.log('✅ Connected to Reverb'));
    };

    initPusher();
})();
