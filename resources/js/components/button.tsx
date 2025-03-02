import {ButtonHTMLAttributes} from "react";
import {cn} from "@/lib/utils";

const Button = ({ className, ...rest }: React.DetailedHTMLProps<ButtonHTMLAttributes<HTMLButtonElement>, HTMLButtonElement>) => {
    return (
        <button
            className={cn(
                'bg-sw-teal disabled:bg-muted-foreground h-7 cursor-pointer rounded-full px-4 font-bold text-white hover:bg-green-700 disabled:cursor-not-allowed',
                className,
            )}
            {...rest}
        ></button>
    );
};


export default Button;
