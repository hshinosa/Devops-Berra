import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { describe, it, expect, vi } from 'vitest';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm';

describe('UpdateProfileInformationForm', () => {
    it('renders form with user data', () => {
        render(<UpdateProfileInformationForm />);

        expect(screen.getByText('Informasi Profil')).toBeInTheDocument();
        expect(screen.getByLabelText('Nama')).toBeInTheDocument();
        expect(screen.getByLabelText('Email')).toBeInTheDocument();
        expect(screen.getByRole('button', { name: 'Simpan' })).toBeInTheDocument();
    });

    it('shows name input with user name', () => {
        render(<UpdateProfileInformationForm />);

        const nameInput = screen.getByLabelText('Nama');
        expect(nameInput).toHaveValue('Test Admin');
    });

    it('shows email input with user email', () => {
        render(<UpdateProfileInformationForm />);

        const emailInput = screen.getByLabelText('Email');
        expect(emailInput).toHaveValue('test@example.com');
    });

    it('allows user to change name', () => {
        render(<UpdateProfileInformationForm />);

        const nameInput = screen.getByLabelText('Nama');
        fireEvent.change(nameInput, { target: { value: 'New Name' } });

        expect(nameInput).toHaveValue('New Name');
    });

    it('allows user to change email', () => {
        render(<UpdateProfileInformationForm />);

        const emailInput = screen.getByLabelText('Email');
        fireEvent.change(emailInput, { target: { value: 'new@example.com' } });

        expect(emailInput).toHaveValue('new@example.com');
    });

    it('submits form when save button clicked', async () => {
        render(<UpdateProfileInformationForm />);

        const saveButton = screen.getByRole('button', { name: 'Simpan' });
        fireEvent.click(saveButton);

        await waitFor(() => {
            expect(screen.getByText('Profil berhasil disimpan.')).toBeInTheDocument();
        });
    });

    it('shows placeholders', () => {
        render(<UpdateProfileInformationForm />);

        expect(screen.getByPlaceholderText('Masukkan nama lengkap')).toBeInTheDocument();
        expect(screen.getByPlaceholderText('contoh@email.com')).toBeInTheDocument();
    });
});
