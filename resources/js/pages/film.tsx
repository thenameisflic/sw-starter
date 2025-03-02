import Divider from '@/components/divider';
import AppLayout from '@/layouts/app-layout';
import { extractIdFromUrl } from '@/lib/utils';
import {CharacterDetails} from "@/pages/people";
import ButtonLink from "@/components/button-link";

interface FilmPageProps {
    id: string;
    response: FilmDetails;
}

export interface FilmDetails {
    title: string;
    opening_crawl: string;
    url: string;
    characters: CharacterDetails[];
}

export default ({ response }: FilmPageProps) => {
    return (
        <AppLayout>
            <div className="bg-background container mx-auto mt-6 rounded p-6 shadow">
                <div className="text-lg font-bold">{response.title}</div>
                <div className="mt-6 flex flex-col lg:flex-row">
                    <div>
                        <div className="text-md font-bold">Opening Crawl</div>
                        <Divider />
                        <p dangerouslySetInnerHTML={{ __html: response.opening_crawl }} className="mt-1 w-96 lg:pr-16 max-w-full"></p>
                    </div>
                    <div className="mt-2 flex-1 lg:mt-0 lg:ml-4">
                        <div className="text-md font-bold">Characters</div>
                        <Divider />
                        <p className="mt-1">
                            {response.characters.map((character, idx) => (
                                <span key={character.url}>
                                    <a
                                        className="cursor:pointer hover:underline text-blue-500"
                                        href={route('people.details', { id: extractIdFromUrl(character.url) })}
                                    >
                                        {character.name}
                                    </a>
                                    {idx + 1 < response.characters.length && <>,&nbsp;</>}
                                </span>
                            ))}
                        </p>
                    </div>
                </div>
                <ButtonLink className="mt-6 max-w-38 text-center items-center justify-center" href={route('home')}>BACK TO SEARCH</ButtonLink>
            </div>
        </AppLayout>
    );
};
