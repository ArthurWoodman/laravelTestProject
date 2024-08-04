import './bootstrap';
import '../css/app.css';

import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import {Provider} from "react-redux";
import Store from "@/Store/Store.js";
//import {createBrowserRouter, RouterProvider} from "react-router-dom";
//import Articles, { loader as getArticles } from "@/Pages/Articles.jsx";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// too much to configure... skipping for now!
// const router = createBrowserRouter([
//     {
//         path: '/articles',
//         element: <Articles />,
//         loader: getArticles
//     }
// ]);

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            // <RouterProvider router={router}>
            <Provider store={Store}>
                <App {...props} />
            </Provider>
            // </RouterProvider>
        );
    },
    progress: {
        color: '#4B5563',
    },
});
