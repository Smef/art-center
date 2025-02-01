type NavLinkBase = {
    label: string;
    icon: object;
    active?: boolean;
    visible: boolean;
};

export type NavLinkWithHref = NavLinkBase & {
    href: string;
};
export type NavLinkWithChildren = NavLinkBase & {
    children: NavLinkWithHref[];
};

type NavLink = NavLinkWithChildren | NavLinkWithHref;
export default NavLink;
