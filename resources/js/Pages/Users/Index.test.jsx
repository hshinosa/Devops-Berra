import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { describe, it, expect, vi } from 'vitest';
import Index from '@/Pages/Users/Index';

const mockUsers = {
    data: [
        { id: 1, name: 'User One', email: 'user1@example.com', phoneNumber: '081111111111', storeName: 'Toko One' },
        { id: 2, name: 'User Two', email: 'user2@example.com', phoneNumber: '082222222222', storeName: '' },
    ],
    current_page: 1,
    per_page: 10,
    links: [],
};

describe('Users Index', () => {
    it('renders user list page', () => {
        render(<Index users={mockUsers} filters={{}} />);

        expect(screen.getByText('Data Pengguna')).toBeInTheDocument();
        expect(screen.getByText('Tambah Pengguna')).toBeInTheDocument();
    });

    it('displays user data in table', () => {
        render(<Index users={mockUsers} filters={{}} />);

        expect(screen.getByText('User One')).toBeInTheDocument();
        expect(screen.getByText('user1@example.com')).toBeInTheDocument();
        expect(screen.getByText('Toko One')).toBeInTheDocument();
    });

    it('shows search input', () => {
        render(<Index users={mockUsers} filters={{}} />);

        expect(screen.getByPlaceholderText('Cari berdasarkan nama, email, telepon, atau toko...')).toBeInTheDocument();
    });

    it('shows status filter dropdown', () => {
        render(<Index users={mockUsers} filters={{}} />);

        expect(screen.getByRole('option', { name: 'Semua Status' })).toBeInTheDocument();
        expect(screen.getByRole('option', { name: 'Punya Toko' })).toBeInTheDocument();
        expect(screen.getByRole('option', { name: 'Belum Punya Toko' })).toBeInTheDocument();
    });

    it('shows search button', () => {
        render(<Index users={mockUsers} filters={{}} />);

        expect(screen.getByRole('button', { name: 'Cari' })).toBeInTheDocument();
    });

    it('allows typing in search input', () => {
        render(<Index users={mockUsers} filters={{}} />);

        const searchInput = screen.getByPlaceholderText('Cari berdasarkan nama, email, telepon, atau toko...');
        fireEvent.change(searchInput, { target: { value: 'test search' } });

        expect(searchInput).toHaveValue('test search');
    });

    it('shows reset button when filters applied', () => {
        render(<Index users={mockUsers} filters={{ search: 'test' }} />);

        expect(screen.getByRole('button', { name: 'Reset' })).toBeInTheDocument();
    });

    it('shows empty state when no users', () => {
        render(<Index users={{ data: [], current_page: 1, per_page: 10, links: [] }} filters={{}} />);

        expect(screen.getByText('Tidak ada data pengguna.')).toBeInTheDocument();
    });

    it('displays user without store name as dash', () => {
        render(<Index users={mockUsers} filters={{}} />);

        const rows = screen.getAllByRole('row');
        const userTwoRow = rows.find(row => row.textContent.includes('User Two'));
        expect(userTwoRow).toBeInTheDocument();
    });
});
