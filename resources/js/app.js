document.querySelectorAll('[data-booking-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const panel = document.getElementById(button.dataset.bookingToggle);
        panel?.classList.toggle('hidden');
        if (panel && !panel.classList.contains('hidden')) {
            panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});

document.querySelectorAll('[data-booking-close]').forEach((button) => {
    button.addEventListener('click', () => document.getElementById(button.dataset.bookingClose)?.classList.add('hidden'));
});

document.querySelectorAll('.booking-form').forEach((form) => {
    const guests = form.querySelector('.guest-count');
    const date = form.querySelector('.tour-date');
    const total = form.querySelector('.booking-total');
    const updateTotal = () => {
        total.textContent = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP', maximumFractionDigits: 0 }).format(Number(form.dataset.price) * Number(guests.value));
    };
    guests.addEventListener('change', updateTotal);
    date?.addEventListener('change', () => {
        const remaining = Number(date.selectedOptions?.[0]?.dataset.remaining || guests.options.length);
        [...guests.options].forEach((option) => option.disabled = Number(option.value) > remaining);
        if (Number(guests.value) > remaining) guests.value = '1';
        updateTotal();
    });
    updateTotal();
});
