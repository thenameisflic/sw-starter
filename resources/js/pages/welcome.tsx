import Button from '@/components/button';
import ButtonLink from '@/components/button-link';
import Divider from '@/components/divider';
import AppLayout from '@/layouts/app-layout';
import { extractIdFromUrl } from '@/lib/utils';
import { useForm } from '@inertiajs/react';
import { motion } from 'motion/react';

interface APIErrors {
    api: string | undefined;
}

export interface APIResult {
    name: string | undefined;
    title: string | undefined;
    url: string;
}

interface APIResponse {
    count: number;
    next: string;
    previous: string;
    results: APIResult[];
}

interface WelcomePageProps {
    response: APIResponse | undefined;
    category: string | undefined;
    query: string | undefined;
}

export default function Welcome({ response, category, query }: WelcomePageProps) {
    const { data, setData, get, processing, errors } = useForm({
        category: category || 'people',
        query: query || '',
    });

    const apiErrors = errors as APIErrors;

    const handleCategoryChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setData('category', event.target.value);
    };

    const handleQueryChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setData('query', event.target.value);
    };

    const onSubmit = (evt: React.FormEvent<HTMLFormElement>) => {
        evt.preventDefault();
        if (!data.query) return;
        get(route('api.query'), {
            preserveState: true,
        });
    };

    return (
        <AppLayout>
            <div className="container mx-auto mt-6">
                <div className="flex flex-col lg:flex-row">
                    <div className="bg-background w-full self-start p-6 lg:w-82">
                        <form onSubmit={(evt) => onSubmit(evt)}>
                            <label className="block text-sm font-semibold">What are you searching for?</label>
                            <div className="mt-4 flex">
                                <div>
                                    <input
                                        type="radio"
                                        id="people"
                                        name="category"
                                        value="people"
                                        checked={data.category === 'people'}
                                        onChange={handleCategoryChange}
                                    />
                                    <label htmlFor="people" className="ml-2 text-sm font-bold">
                                        People
                                    </label>
                                </div>
                                <div className="ml-10">
                                    <input
                                        type="radio"
                                        id="films"
                                        name="category"
                                        value="films"
                                        checked={data.category === 'films'}
                                        onChange={handleCategoryChange}
                                    />
                                    <label htmlFor="films" className="ml-2 text-sm font-bold">
                                        Movies
                                    </label>
                                </div>
                            </div>
                            <input
                                type="text"
                                placeholder={data.category === 'people' ? 'e.g. Chewbacca, Yoda, Boba Fett' : ' e.g. Return of the Jedi, A New Hope'}
                                className="placeholder:text-muted-foreground mt-4 h-8 w-full rounded border border-solid px-2 text-sm font-bold"
                                value={data.query}
                                onChange={handleQueryChange}
                            ></input>
                            <Button type="submit" disabled={data.query.length === 0} className="mt-4 w-full">
                                {processing ? <div className="animate-pulse">SEARCHING...</div> : 'SEARCH'}
                            </Button>
                        </form>
                        <div className="mt-4 text-red-500">{apiErrors.api}</div>
                    </div>
                    <div className="bg-background w-full flex-1 p-6 lg:ml-6">
                        <div className="text font-bold">Results</div>
                        <Divider />
                        {!response?.count && (
                            <div className="flex h-64 items-center justify-center">
                                <div className="text-muted-foreground text-center text-sm font-bold">
                                    {processing && <p className="animate-pulse">Searching...</p>}
                                    {!processing && (
                                        <p>
                                            There are zero matches.
                                            <br /> Use the form to search for People or Movies.
                                        </p>
                                    )}
                                </div>
                            </div>
                        )}
                        {response && response.count > 0 && (
                            <ul className="">
                                {response.results.map((result, index) => (
                                    <motion.li
                                        className={'border-b-muted-foreground border-b-1 py-2 font-bold last:border-b-0'}
                                        key={(result.name || result.title || 'unknown') + index}
                                        initial={{ opacity: 0, y: 20 }}
                                        animate={{ opacity: 1, y: 0 }}
                                        transition={{ delay: index * 0.12, duration: 0.5 }}
                                    >
                                        <div className="flex items-center">
                                            <p className="flex-1">{result.name || result.title}</p>
                                            <motion.div
                                                initial={{ opacity: 0 }}
                                                animate={{ opacity: 1 }}
                                                transition={{ delay: response.results.length * 0.12 + 0.5 }}
                                            >
                                                <ButtonLink
                                                    href={
                                                        category === 'films'
                                                            ? route('film.details', { id: extractIdFromUrl(result.url) })
                                                            : route('people.details', { id: extractIdFromUrl(result.url) })
                                                    }
                                                >
                                                    See details
                                                </ButtonLink>
                                            </motion.div>
                                        </div>
                                    </motion.li>
                                ))}
                            </ul>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
