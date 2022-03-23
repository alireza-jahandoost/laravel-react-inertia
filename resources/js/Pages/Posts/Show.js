import React from 'react';
import Authenticated from '@/Layouts/Authenticated';
import { Head } from '@inertiajs/inertia-react';

export default function Show({post}) {
    return (
        <>
            <Head title={post.title} />
            <h1>{post.title}</h1>
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        {post.content}
                    </div>
                </div>
            </div>
        </>
    );
}
