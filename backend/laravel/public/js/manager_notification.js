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

            const message = `🚀 Новый лид:
Имя: ${payload.name}
Телефон: ${payload.phone}
Email: ${payload.email}`;

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

            new Audio('/sounds/notification.mp3').play();

            window.dispatchEvent(new CustomEvent("async-reload"));
        });

        pusher.connection.bind('connected');
    };

    initPusher();
})();
