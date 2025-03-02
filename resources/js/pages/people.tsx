import AppLayout from "@/layouts/app-layout";
import Divider from "@/components/divider";
import {extractIdFromUrl} from "@/lib/utils";
import {FilmDetails} from "@/pages/film";
import ButtonLink from "@/components/button-link";

interface PeoplePageProps {
    id: string;
    response: CharacterDetails;
}

export interface CharacterDetails {
    name: string;
    url: string;
    films: FilmDetails[];
    birth_year: string;
    gender: string;
    eye_color: string;
    hair_color: string;
    height: string;
    mass: string;
}

export default ({ response }: PeoplePageProps) => {
    console.log(response);
    return (
        <AppLayout>
            <div className="bg-background container mx-auto mt-6 rounded p-6 shadow">
                <div className="text-lg font-bold">{response.name}</div>
                <div className="mt-6 flex flex-col lg:flex-row">
                    <div>
                        <div className="text-md font-bold">Details</div>
                        <Divider />
                        <div className="mt-1 w-96 lg:pr-16 max-w-full">
                            <p>Birth Year: {response.birth_year}</p>
                            <p>Gender: {response.gender}</p>
                            <p>Eye Color: {response.eye_color}</p>
                            <p>Hair Color: {response.hair_color}</p>
                            <p>Height: {response.height}</p>
                            <p>Mass: {response.mass}</p>
                        </div>
                    </div>
                    <div className="mt-2 flex-1 lg:mt-0 lg:ml-4">
                        <div className="text-md font-bold">Movies</div>
                        <Divider />
                        <p className="mt-1">
                            {response.films.map((film, idx) => (
                                <span key={film.url}>
                                    <a
                                        className="cursor:pointer hover:underline text-blue-500"
                                        href={route('film.details', { id: extractIdFromUrl(film.url) })}
                                    >
                                        {film.title}
                                    </a>
                                    {idx + 1 < response.films.length && <>,&nbsp;</>}
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
