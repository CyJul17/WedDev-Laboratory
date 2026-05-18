import Link from 'next/link';
import SearchBar from './components/SearchBar';


function typeColor(type: string) {

    const colors: {[key: string]: string } = {

        fire: "bg-orange-600",
        water: "bg-blue-500",
        grass: "bg-green-500",
        electric: "bg-yellow-400 text-black",
        psychic: "bg-pink-400",
        ice: "bg-cyan-300 text-black",
        dragon: "bg-indigo-600",
        dark: "bg-zinc-700",
        fairy: "bg-pink-300 text-black",
        normal: "bg-zinc-400 text-black",
        fighting: "bg-red-700",
        flying: "bg-sky-400 text-black",
        poison: "bg-purple-500",
        ground: "bg-amber-600",
        rock: "bg-stone-600",
        bug: "bg-lime-500 text-black",
        ghost: "bg-violet-700",
        steel: "bg-slate-400 text-black"
    };
    return colors[type] || "bg-gray-500";
}

interface Pokemon { // the interface OoO

  id: number;
  name: string;
  height: number;
  weight: number;
  sprites: {

    front_default: string;
  };

  types: {
    type: {

      name: string;
    };
  }[];
}

  const PAGE_SIZE = 32;
  const TOTAL_POKEMON = 1025;

// We fetch the data (Sana all na fetch)

async function getPokemonData(offset = 0, search = ""): Promise<Pokemon[]> {

  try {

    if (search) {
  
      const res = await fetch(`https://pokeapi.co/api/v2/pokemon?limit=1025`); 
      if (!res.ok) return[];
      const data = await res.json();
      const filteredResults = data.results.filter((p: {name: string}) =>

        p.name.startsWith(search.toLowerCase())
      );
      const matches = filteredResults.slice(0, 20);
      const detailPromises = matches.map(async (p: {url: string}) => {

        const response = await fetch(p.url);
        return response.json();
      });
      return Promise.all(detailPromises);
    }
    
    const res = await fetch(`https://pokeapi.co/api/v2/pokemon?limit=${PAGE_SIZE}&offset=${offset}`); 
  
    const listData = await res.json();
    const detailPromises = listData.results.map(async (p: { url: string})=> {
  
    const response = await fetch(p.url);
    return response.json();
    });
  
    return Promise.all(detailPromises); // Promise this will return XD
  } catch {
    
    return [];
  }
}

// Bahay pahina

export default async function Home({

  searchParams,
}: {
  searchParams: Promise<{ page?: string; search?: string}>;
}) {
  
  const params = await searchParams;
  const currentPage = Number(params.page) || 0;
  const search = params.search || "";
  const totalPages = Math.ceil(TOTAL_POKEMON / PAGE_SIZE);
  const startPage = Math.max(0, currentPage - 2);
  const endPage = Math.min(totalPages - 1, currentPage + 2);
  const pageNum = [];

  for (let i = startPage; i <= endPage; i++) {

    pageNum.push(i);
  }
  const offset = currentPage * PAGE_SIZE;
  const allPokemon = await getPokemonData(offset, search);
  const next = currentPage < totalPages - 1;

  return (

    <div className='min-h-screen bg-zinc-50 dark:bg-black font-sans p-8'>
      <main className='max-w-5xl mx-auto'>

        <header className='mb-12 -mx-8 p-8 bg-red-600 dark:bg-red-700 shadow-lg sm:rounded-3xl text-center sm:text-left'>
          <Link href="/" className='inline-block w-fit'>
          <h1 className='text-4xl font-bold tracking-tight text-black dark:text-white'>Pokedex</h1>
          </Link>
          <p className='text-zinc-600 dark:text-zinc-400 mt-2'>Build using Next.js and PokeAPI</p>
        </header>

        <SearchBar />
        
        <div className='grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4'>
          {allPokemon.length > 0 ? (
          allPokemon.map((pokemon)=> (
            <Link href={`/pokemon/${pokemon.id}?page=${currentPage}`} key={pokemon.id}>
              <div className='group flex flex-col items-center p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl transition-all hover:shadow-lg hover:border-zinc-400 dark:hover:border-zinc-600'>

                <img
                src={pokemon.sprites.front_default}
                alt={pokemon.name}
                className='w-32 h-32 transition-transform group-hover:scale-110'
                />

                <div className='flex gap-2 mt-2'>
                  {pokemon.types.map((t)=>(
                    <span key={t.type.name} className={`text-xs font-bold px-2 py-1 rounded-lg uppercase tracking-widest shadow-md ${typeColor(t.type.name)}`}>
                      {t.type.name}
                    </span>
                  ))}

                </div>
                <h2 className='mt-4 text-xl font-semibold capitalize text-zinc-900 dark:text-zinc-100'>{pokemon.name}</h2>
                <div className='mt-2 flex gap-4 text-sm text-zinc-500'>
                  <span>H: {pokemon.height / 10}m</span>
                  <span>W: {pokemon.weight / 10}kg</span>
                </div>
              </div>
            </Link>
          ))
        ) : (

          <p className='text-zinc-500'>No pokemon with that name boi</p>
        )}
        </div>

        {!search && allPokemon.length > 0 && (
          
        <div className='mt-12 flex flex-col items-center gap-4'>

          <p className='text-zinc-500 dark:text-zinc-400 font-medium'>
            Page <span className='text-black dark:text-white'>{currentPage + 1}</span> of {totalPages}
          </p>

          <div className='flex justify-center gap-4'>
          {currentPage > 0 ? (
            <Link href={`/?page=${currentPage - 1}${search ? `search=${search}` : ''}`} 
            className='px-6 py-2 bg-zinc-800 text-white rounded-lg hover:bg-zinc-700'>
              Prev
            </Link>
          ): (
            <button className='px-6 py-2 bg-zinc-200 dark:bg-zinc-800 text-zinc-400 rounded-lg cursor-not-allowed disabled'>
              Prev
            </button>
          )}

          <div className='flex gap-1 sm:gap-2'>
            {pageNum.map((num) => (
              
              <Link 
              key={num}
              href={`/?page=${num}`}
              className={`w-10 h-10 flex items-center justify-center rounded-lg font-bold transition-all ${
                currentPage === num
                ? "bg-red-800 text-white border-2 border-red-700 shadow-md" :
                "bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-400 hover:border-zinc-400"
              }`}>
                {num + 1}
              </Link>
            ))}
          </div>

          {next ? (
            <Link href={`/?page=${currentPage + 1}${search ? `search=${search}` : ''}`} 
            className='px-6 py-2 bg-zinc-800 text-white rounded-lg hover:bg-zinc-700'>
              Next
            </Link>
          ): (
            <button className='px-6 py-2 bg-zinc-200 dark:bg-zinc-800 text-zinc-400 rounded-lg cursor-not-allowed'disabled>
              Next
            </button>
          )}
          </div>
        </div>
        )}
      </main>
    </div>
  );
}