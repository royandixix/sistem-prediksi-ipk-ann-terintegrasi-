import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const GREEN = '#0f6b5b';

const escapeHtml = (value) => {
    const element = document.createElement('div');

    element.textContent = String(value);

    return element.innerHTML;
};

const showValidationErrors = (errors) => {
    const items = errors
        .map((error) => `<li>${escapeHtml(error)}</li>`)
        .join('');

    return Swal.fire({
        icon: 'error',
        title: 'Data login belum valid',
        html: `
            <ul style="
                margin: 0;
                padding-left: 1.25rem;
                text-align: left;
            ">
                ${items}
            </ul>
        `,
        confirmButtonText: 'Periksa Kembali',
        confirmButtonColor: GREEN,
    });
};

const initializeFlashMessages = () => {
    const alerts = window.AppAlerts ?? {};
    const validationErrors = Array.isArray(alerts.validationErrors)
        ? alerts.validationErrors
        : [];

    if (validationErrors.length > 0) {
        showValidationErrors(validationErrors);

        return;
    }

    if (alerts.error) {
        Swal.fire({
            icon: 'error',
            title: 'Proses gagal',
            text: alerts.error,
            confirmButtonText: 'Tutup',
            confirmButtonColor: GREEN,
        });

        return;
    }

    if (alerts.warning) {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: alerts.warning,
            confirmButtonText: 'Mengerti',
            confirmButtonColor: GREEN,
        });

        return;
    }

    if (alerts.success) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: alerts.success,
            confirmButtonText: 'Lanjutkan',
            confirmButtonColor: GREEN,
            timer: 2200,
            timerProgressBar: true,
        });
    }
};

const initializeLoginValidation = () => {
    const form = document.querySelector('[data-login-form]');

    if (! form) {
        return;
    }

    const username = form.querySelector('[name="username"]');
    const password = form.querySelector('[name="password"]');
    const submitButton = form.querySelector('[data-login-submit]');

    form.addEventListener('submit', (event) => {
        const errors = [];
        const usernameValue = username?.value.trim() ?? '';
        const passwordValue = password?.value ?? '';

        if (! usernameValue) {
            errors.push('Username wajib diisi.');
        } else {
            if (usernameValue.length < 4) {
                errors.push('Username minimal terdiri dari 4 karakter.');
            }

            if (usernameValue.length > 50) {
                errors.push('Username maksimal terdiri dari 50 karakter.');
            }

            if (! /^[A-Za-z0-9._-]+$/.test(usernameValue)) {
                errors.push(
                    'Username hanya boleh berisi huruf, angka, titik, garis bawah, dan tanda hubung.',
                );
            }
        }

        if (! passwordValue) {
            errors.push('Password wajib diisi.');
        } else if (passwordValue.length < 8) {
            errors.push('Password minimal terdiri dari 8 karakter.');
        }

        if (errors.length > 0) {
            event.preventDefault();

            showValidationErrors(errors);

            return;
        }

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.classList.add('cursor-not-allowed', 'opacity-70');
            submitButton.textContent = 'Memproses...';
        }
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initializeFlashMessages();
    initializeLoginValidation();
});