import {AnchorHTMLAttributes} from "react";
import {cn} from "@/lib/utils";

const ButtonLink = (props: React.DetailedHTMLProps<AnchorHTMLAttributes<HTMLAnchorElement>, HTMLAnchorElement>) => {
    return (
        <a
            {...props}
            className={
                cn('block flex items-center bg-sw-teal disabled:bg-muted-foreground h-7 cursor-pointer rounded-full px-4 font-bold text-white hover:bg-green-700 disabled:cursor-not-allowed', props.className)
            }
        />
    );
}

export default ButtonLink;
