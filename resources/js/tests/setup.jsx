import '@testing-library/jest-dom';
import { vi } from 'vitest';
import React from 'react';

vi.mock('@inertiajs/react', () => ({
    usePage: () => ({
        props: {
            auth: { user: { id: 1, name: 'Test Admin', email: 'test@example.com', is_super_admin: true } },
            is_super_admin: true,
            flash: {},
        },
        url: '/dashboard',
    }),
    router: {
        post: vi.fn(),
        put: vi.fn(),
        patch: vi.fn(),
        delete: vi.fn(),
        visit: vi.fn(),
        reload: vi.fn(),
        get: vi.fn(),
    },
    Link: ({ children, href, ...props }) => React.createElement('a', { href, ...props }, children),
    useForm: (initialData) => ({
        data: initialData,
        setData: vi.fn((key, value) => {
            if (typeof key === 'object') {
                Object.assign(initialData, key);
            } else {
                initialData[key] = value;
            }
        }),
        post: vi.fn((url, options) => {
            if (options?.onSuccess) options.onSuccess();
        }),
        patch: vi.fn((url, options) => {
            if (options?.onSuccess) options.onSuccess();
        }),
        put: vi.fn((url, options) => {
            if (options?.onSuccess) options.onSuccess();
        }),
        delete: vi.fn((url, options) => {
            if (options?.onSuccess) options.onSuccess();
        }),
        processing: false,
        errors: {},
        reset: vi.fn(),
        clearErrors: vi.fn(),
        recentlySuccessful: true,
    }),
    Head: ({ title }) => title,
}));

vi.mock('@headlessui/react', () => ({
    Transition: ({ children, show }) => show ? children : null,
    Menu: ({ children }) => children,
    MenuButton: ({ children }) => children,
    MenuItems: ({ children }) => children,
    MenuItem: ({ children }) => children,
}));

const routeMock = vi.fn((name, params) => {
    const routes = {
        'profile.edit': '/profile/edit',
        'profile.update': '/profile',
        'profile.destroy': '/profile',
        'password.update': '/user/password',
        'users.index': '/users',
        'users.store': '/users',
        'users.create': '/users/create',
        'users.show': `/users/${params?.id || 1}`,
        'users.update': `/users/${params?.id || 1}`,
        'users.destroy': `/users/${params?.id || 1}`,
        'dashboard': '/dashboard',
        'logout': '/logout',
    };
    const url = routes[name] || '/';
    return url;
});

routeMock.current = vi.fn(() => '');

global.route = routeMock;

vi.mock('axios', () => ({
    default: {
        get: vi.fn(() => Promise.resolve({ data: {} })),
        post: vi.fn(() => Promise.resolve({ data: {} })),
        put: vi.fn(() => Promise.resolve({ data: {} })),
        patch: vi.fn(() => Promise.resolve({ data: {} })),
        delete: vi.fn(() => Promise.resolve({ data: {} })),
    },
}));
