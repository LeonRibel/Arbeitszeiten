import {
    HomeIcon,
    CalendarDaysIcon,
    UserIcon,
    PlusIcon,
    Cog8ToothIcon,
    InboxIcon
} from '@heroicons/react/24/outline'



interface NavItem {
    name: string
    href: string
    icon: any
    description: string
    className?: string
}


export const navigationItems: NavItem[] = [
    { name: 'Dashboard', href: '/', icon: HomeIcon, description: 'Übersicht deiner Arbeitszeiten', className: 'search-text' },
    { name: 'Neue Arbeitszeit', href: '/neu', icon: PlusIcon, description: 'Neue Arbeitszeit erfassen', className: 'search-text' },
    { name: 'Fehlzeiten', href: '/Fehlzeiten', icon: CalendarDaysIcon, description: 'Fehlzeiten', className: 'search-text' },
    { name: 'Fehlzeiten eintragen', icon: PlusIcon, href: '/Fehlzeiten/neu', description: 'Fehlzeiten eintragen', className: 'search-text' },
    { name: 'Urlaub', href: '/Urlaub', icon: CalendarDaysIcon, description: 'Urlaubsanträge', className: 'search-text' },
    { name: 'Urlaub eintragen', icon: PlusIcon, href: '/Urlaub/neu', description: 'Urlaub eintragen', className: 'search-text' },
    { name: 'Mitarbeiter', href: '/Mitarbeiter', icon: UserIcon, description: 'Mitarbeiter', className: 'search-text' },
    { name: 'Mitarbeiter eintragen', icon: UserIcon, href: '/Mitarbeiter/neu', description: 'Mitarbeiter eintragen', className: 'search-text' },
    { name: 'Überstunden', href: '/Ueberstunden', icon: PlusIcon, description: 'Überstunden', className: 'search-text' },
    { name: 'Mein Profil', href: '/MeinProfil', icon: UserIcon, description: 'Profil', className: 'search-text' },
    { name: 'Einstellungen', href: '/settings', icon: Cog8ToothIcon, description: 'Einstellungen', className: 'search-text' },
    { name: 'Inbox', href: '/inbox', icon: InboxIcon, description: 'Nachrichten', className: 'search-text' },
]

export default navigationItems