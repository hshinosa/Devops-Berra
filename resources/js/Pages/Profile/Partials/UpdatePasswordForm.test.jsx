import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { describe, it, expect, vi } from 'vitest';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm';

describe('UpdatePasswordForm', () => {
    it('renders form with all fields', () => {
        render(<UpdatePasswordForm />);

        expect(screen.getByText('Ubah Password')).toBeInTheDocument();
        expect(screen.getByLabelText('Password Saat Ini')).toBeInTheDocument();
        expect(screen.getByLabelText('Password Baru')).toBeInTheDocument();
        expect(screen.getByLabelText('Konfirmasi Password Baru')).toBeInTheDocument();
    });

    it('shows save button', () => {
        render(<UpdatePasswordForm />);

        expect(screen.getByRole('button', { name: 'Simpan' })).toBeInTheDocument();
    });

    it('allows user to enter current password', () => {
        render(<UpdatePasswordForm />);

        const currentPasswordInput = screen.getByLabelText('Password Saat Ini');
        fireEvent.change(currentPasswordInput, { target: { value: 'oldpassword123' } });

        expect(currentPasswordInput).toHaveValue('oldpassword123');
    });

    it('allows user to enter new password', () => {
        render(<UpdatePasswordForm />);

        const newPasswordInput = screen.getByLabelText('Password Baru');
        fireEvent.change(newPasswordInput, { target: { value: 'newpassword123' } });

        expect(newPasswordInput).toHaveValue('newpassword123');
    });

    it('allows user to confirm new password', () => {
        render(<UpdatePasswordForm />);

        const confirmPasswordInput = screen.getByLabelText('Konfirmasi Password Baru');
        fireEvent.change(confirmPasswordInput, { target: { value: 'newpassword123' } });

        expect(confirmPasswordInput).toHaveValue('newpassword123');
    });

    it('submits form when save button clicked', async () => {
        render(<UpdatePasswordForm />);

        const saveButton = screen.getByRole('button', { name: 'Simpan' });
        fireEvent.click(saveButton);

        await waitFor(() => {
            expect(screen.getByText('Password berhasil diubah.')).toBeInTheDocument();
        });
    });

    it('shows placeholders', () => {
        render(<UpdatePasswordForm />);

        expect(screen.getByPlaceholderText('Masukkan password saat ini')).toBeInTheDocument();
        expect(screen.getByPlaceholderText('Minimal 8 karakter')).toBeInTheDocument();
        expect(screen.getByPlaceholderText('Ulangi password baru')).toBeInTheDocument();
    });
});
