"use client";

import {useRouter, useSearchParams} from 'next/navigation';
import {useTransition} from 'react';

export default function SearchBar() {

    const router = useRouter();
    const searchParams = useSearchParams();
    const [isPending, startTransition] = useTransition();

    function handleSearch(term: string) {

        const params = new URLSearchParams(searchParams);

        if(term) {

            params.set('search', term.toLowerCase());
            params.set('page', '0'); // reset the page to 0
        } else {

            params.delete('search');
        }

        startTransition(() => {

            router.push(`/?${params.toString()}`);
        });
    }

    return(
        <div className='relative w-full max-w-md mb-8'>
            <input type='text' 
            placeholder='This is a search bar, duh' 
            className='w-full p-4 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 focus:outline-none focus:ring-yellow-400 dark:text-white'
            onChange={(e) => handleSearch(e.target.value)}
            defaultValue = {searchParams.get('search') || ""}
            />
            {isPending && <div className='absolute right-4 top-4 animate-spin'><img src="/Pokeball.png" alt='loading' className='w-6 h-6 object-contain opacity-80'/></div>}
        </div>
    );
}