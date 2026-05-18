import Link from "next/link";

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

async function getPokemonDetails(id: string) {

    const res = await fetch(`https://pokeapi.co/api/v2/pokemon/${id}`);

    if (!res.ok) return null;
    return res.json();
}

export default async function PokemonDetails({
    params,
    searchParams
}: { 
    params: {id: string},
    searchParams: Promise<{page?: string}>
 }) {

    const { id } = await params;
    const {page} = await searchParams;

    const pokemon = await getPokemonDetails(id);

if (!pokemon) return <div className="p-10"> No Pokemon bro.</div>;
const returnPage = page || "0";

return (

    <div className="min-h-screen bg-gray-900 text-white p-10 flex flex-col items-center">
       <Link
       href={`/?page=${returnPage}`}
       className="mb-6 text-blue-400 hover:underline">
        Return to the Pokedex
       </Link>
        <div className="bg-gray-800 border-2 border-yellow-400 rounded-3xl p-10 max-w-md w-full text-center shadow-2xl">
            <h1 className="text-5xl font-black capitalize mb-4">{pokemon.name}</h1>

            <img
            src={pokemon.sprites.other['official-artwork'].front_default}
            alt={pokemon.name}
            className="w-64 h-64 mx-auto mb-6"
            />

            <div className="flex justify-center gap-2 mb-6">
                {pokemon.types.map((t:any) => (

                    <span key={t.type.name} className={`px-4 py-1 rounded-full text-sm font-bold uppercase tracking-widest shadow-md ${typeColor(t.type.name)}`}>
                        {t.type.name}
                    </span>
                ))}
            </div>
            
            <div className="grid grid-cols-2 gap-4 text-xl">
                <div className="bg-gray-700 p-4 rounded-xl">
                    <p className="text-gray-400 text-sm">Height</p>
                    <p className="font-bold">{pokemon.height / 10} m</p>
                </div>
                <div className="bg-gray-700 p-4 rounded-xl">
                    <p className="text-gray-400 text-sm">Weight</p>
                    <p className="font-bold">{pokemon.weight / 10} kg</p>
                </div>
            </div>
        </div>
    </div>
);
}