import {
  Links,
  Meta,
  Outlet,
  Scripts,
  ScrollRestoration,
  useLocation,
} from 'react-router'
import './index.css'
import { useState, useEffect, useMemo, useRef } from 'react'
import { useTranslation } from 'react-i18next'
import fetchApi from './fetchApi'

import { Avatar } from '@catalyst/avatar'
import {
  Dropdown,
  DropdownButton,
  DropdownDivider,
  DropdownItem,
  DropdownLabel,
  DropdownMenu,
} from '@catalyst/dropdown'
import {
  Sidebar,
  SidebarBody,
  SidebarFooter,
  SidebarHeader,
  SidebarItem,
  SidebarLabel,
  SidebarSection,
  SidebarSpacer,
} from '@catalyst/sidebar'
import {
  ArrowRightStartOnRectangleIcon,
  CalendarDaysIcon,
  ChevronDownIcon,
  ChevronUpIcon,
  Cog8ToothIcon,
  LightBulbIcon,
  PlusIcon,
  ShieldCheckIcon,
  UserIcon,
} from '@heroicons/react/16/solid'
import {
  HomeIcon,
  InboxIcon,
  MagnifyingGlassIcon,
  QuestionMarkCircleIcon,
  SparklesIcon,
} from '@heroicons/react/20/solid'
import { SidebarLayout } from '@catalyst/sidebar-layout'
import { Navbar, NavbarSpacer, NavbarSection, NavbarItem } from '@catalyst/navbar'
import './i18n'
import { navigationItems } from './routes/NavItem'

export function Layout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="de">
      <head>
        <meta charSet="UTF-8" />
        <link rel="icon" type="image/svg+xml" href="/vite.svg" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>arbeitszeit</title>
        <Meta />
        <Links />
      </head>
      <body>
        {children}
        <ScrollRestoration />
        <Scripts />
      </body>
    </html>
  )
}

export default function Root() {
  const { t, i18n } = useTranslation()
  const location = useLocation()

  const [user, setUser] = useState<any>(null)

  const isAuthPage = location.pathname === '/login' || location.pathname === '/logout' || location.pathname === '/register'

  useEffect(() => {
    const savedLang = localStorage.getItem('language')
    if (savedLang && i18n?.language !== savedLang) {
      i18n.changeLanguage(savedLang)
    }
  }, [i18n])

  useEffect(() => {
    const applyTheme = () => {
      const saved = localStorage.getItem('themeMode') || 'system'

      if (saved === 'system') {
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
        if (systemPrefersDark) {
          document.documentElement.classList.add('dark')
        } else {
          document.documentElement.classList.remove('dark')
        }
      } else if (saved === 'dark') {
        document.documentElement.classList.add('dark')
      } else {
        document.documentElement.classList.remove('dark')
      }
    }

    applyTheme()

    const themeMode = localStorage.getItem('themeMode') || 'system'
    if (themeMode === 'system') {
      const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
      const handleChange = () => applyTheme()
      mediaQuery.addEventListener('change', handleChange)
      return () => mediaQuery.removeEventListener('change', handleChange)
    }
  }, [])

  useEffect(() => {
    if (isAuthPage) return

    fetchApi('/MeinProfil')
      .then(data => {
        console.log('User Data:', data)
        console.log('User Object:', data.profil?.[0])
        console.log('Vorname:', data.profil?.[0]?.Vorname)
        console.log('email:', data.profil?.[0]?.email)
        setUser(data.profil?.[0])
      })
      .catch(err => console.error(err))
  }, [isAuthPage])

  if (isAuthPage) {
    return <Outlet />
  }

  function SidebarSearch() {
    const [open, setOpen] = useState(false)
    const [query, setQuery] = useState('')
    const inputRef = useRef<HTMLInputElement | null>(null)

    useEffect(() => { if (open) inputRef.current?.focus() }, [open])

    const results = useMemo(() => {
      const q = query.trim().toLowerCase()
      if (!q) return []
      return navigationItems
        .filter(i => (i.name || '').toLowerCase().includes(q) || (i.description || '').toLowerCase().includes(q))
        .slice(0, 6)
    }, [query])

    return (
      <div className="searchbar-wrapper relative flex items-center gap-2">
        <button
          onClick={() => setOpen(o => !o)}
          aria-label="Suche öffnen"
          className="p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800"
        >
          <MagnifyingGlassIcon className="w-5 h-5 text-zinc-600 dark:text-zinc-300" />
        </button>

        {open && (
          <input
            ref={inputRef}
            type="text"
            placeholder="Suche..."
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            className="searchbar w-full pl-3 pr-3 py-2 rounded border border-zinc-200 dark:border-zinc-800 bg-zinc-900 text-white dark:bg-zinc-900 focus:outline-none"
          />
        )}

        {open && results.length > 0 && (
          <div className="searchbar absolute left-0 top-full mt-2 z-30 w-64 bg-zinc-900 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded shadow overflow-hidden">
            {results.map(item => {
              const Icon = item.icon
              return (
                <a
                  key={item.href}
                  href={item.href}
                  onClick={() => { setQuery(''); setOpen(false) }}
                  className="flex items-center gap-3 p-2 hover:bg-zinc-800/50 dark:hover:bg-zinc-800/50"
                >
                  <div className="p-1 rounded bg-zinc-800 dark:bg-zinc-800">
                    <Icon className="w-4 h-4 text-zinc-400 dark:text-zinc-400" />
                  </div>
                  <div className="min-w-0">
                    <div className="text-sm font-medium text-white dark:text-white">{item.name}</div>
                    <div className="text-xs text-zinc-400 dark:text-zinc-400">{item.description}</div>
                  </div>
                </a>
              )
            })}
          </div>
        )}

        {open && (
          <div onClick={() => setOpen(false)} className="fixed inset-0 z-20" aria-hidden="true" />
        )}
      </div>
    )
  }

  return (
    <SidebarLayout
      navbar={
        <Navbar>
          <NavbarSpacer />
          <NavbarSection>
            <NavbarItem href="/search" aria-label="Search">
              <MagnifyingGlassIcon />
            </NavbarItem>
            <NavbarItem href="/inbox" aria-label="Inbox">
              <InboxIcon />
            </NavbarItem>
            <Dropdown>
              <DropdownButton as={NavbarItem}>
                <Avatar src="https://imgs.search.brave.com/JrBYBoKyuee7uVnY4_liHNSM7pot6TZpAPiLDwLKk2U/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/cHJlbWl1bS1wc2Qv/M2QtcmVuZGVyLWF2/YXRhci1jaGFyYWN0/ZXJfMjMtMjE1MDYx/MTc4My5qcGc_c2Vt/dD1haXNfaHlicmlk/Jnc9NzQwJnE9ODA" square />
              </DropdownButton>
              <DropdownMenu className="min-w-64" anchor="bottom end">
                <DropdownItem href="/my-profile">
                  <UserIcon />
                  <DropdownLabel>My profile</DropdownLabel>
                </DropdownItem>
                <DropdownItem href="/settings">
                  <Cog8ToothIcon />
                  <DropdownLabel>Settings</DropdownLabel>
                </DropdownItem>
                <DropdownDivider />
                <DropdownItem href="/privacy-policy">
                  <ShieldCheckIcon />
                  <DropdownLabel>Privacy policy</DropdownLabel>
                </DropdownItem>
                <DropdownItem href="/share-feedback">
                  <LightBulbIcon />
                  <DropdownLabel>Share feedback</DropdownLabel>
                </DropdownItem>
                <DropdownDivider />
                <DropdownItem href="/logout">
                  <ArrowRightStartOnRectangleIcon />
                  <DropdownLabel>Sign out</DropdownLabel>
                </DropdownItem>
              </DropdownMenu>
            </Dropdown>
          </NavbarSection>
        </Navbar>
      }
      sidebar={
        <Sidebar>
          <SidebarHeader>
            <Dropdown>
              <DropdownButton as={SidebarItem} className="lg:mb-2.5">
                <Avatar src="https://imgs.search.brave.com/JrBYBoKyuee7uVnY4_liHNSM7pot6TZpAPiLDwLKk2U/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/cHJlbWl1bS1wc2Qv/M2QtcmVuZGVyLWF2/YXRhci1jaGFyYWN0/ZXJfMjMtMjE1MDYx/MTc4My5qcGc_c2Vt/dD1haXNfaHlicmlk/Jnc9NzQwJnE9ODA" />
                <SidebarLabel>{user ? `${user.Vorname} ${user.Nachname}` : 'Vorname Nachname'}</SidebarLabel>
                <ChevronDownIcon />
              </DropdownButton>
              <DropdownMenu className="min-w-80 lg:min-w-64" anchor="bottom start">
                <DropdownItem href="/settings">
                  <Cog8ToothIcon />
                  <DropdownLabel>Settings</DropdownLabel>
                </DropdownItem>
                <DropdownDivider />
                
                <DropdownDivider />
                <DropdownItem href="/teams/create">
                  <PlusIcon />
                  <DropdownLabel>New team…</DropdownLabel>
                </DropdownItem>
              </DropdownMenu>
            </Dropdown>

            <SidebarSection className="max-lg:hidden">
              <SidebarSearch />
            </SidebarSection>
          </SidebarHeader>

          <SidebarBody>
            <SidebarSection>
              <SidebarItem href="/">
                <HomeIcon />
                <SidebarLabel>{t('dashboard')}</SidebarLabel>
              </SidebarItem>
              <SidebarItem href="/Fehlzeiten">
                <CalendarDaysIcon />
                <SidebarLabel>{t('fehlzeiten')}</SidebarLabel>
              </SidebarItem>
              <SidebarItem href="/Urlaub">
                <CalendarDaysIcon />
                <SidebarLabel>{t('urlaubsantraege')}</SidebarLabel>
              </SidebarItem>
              <SidebarItem href="/Mitarbeiter">
                <UserIcon />
                <SidebarLabel>{t('mitarbeiter')}</SidebarLabel>
              </SidebarItem>
              <SidebarItem href="/Ueberstunden">
                <PlusIcon />
                <SidebarLabel>{t('ueberstunden')}</SidebarLabel>
              </SidebarItem>
              <SidebarItem href="/MeinProfil">
                <UserIcon />
                <SidebarLabel>{t('myProfile')}</SidebarLabel>
              </SidebarItem>
              <SidebarItem href="/projekte">
                <UserIcon/>
                <SidebarLabel >{t('projects')}</SidebarLabel>
              </SidebarItem>
              <SidebarItem href="/kunden">
                <UserIcon />
                <SidebarLabel>{t('customers')}</SidebarLabel>
              </SidebarItem>
            </SidebarSection>

            <SidebarSection className="max-lg:hidden"></SidebarSection>
            <SidebarSpacer />

            <SidebarSection>
              <SidebarItem href="https://ape-dev.de/" target="_blank">
                <QuestionMarkCircleIcon />
                <SidebarLabel>creator</SidebarLabel>
              </SidebarItem>
              <SidebarItem href="/settings">
                <SparklesIcon />
                <SidebarLabel>{t('einstellungen')}</SidebarLabel>
              </SidebarItem>
            </SidebarSection>
          </SidebarBody>

          <SidebarFooter className="max-lg:hidden">
            <Dropdown>
              <DropdownButton as={SidebarItem}>
                <span className="flex min-w-0 items-center gap-3">
                  <Avatar src="https://imgs.search.brave.com/JrBYBoKyuee7uVnY4_liHNSM7pot6TZpAPiLDwLKk2U/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/cHJlbWl1bS1wc2Qv/M2QtcmVuZGVyLWF2/YXRhci1jaGFyYWN0/ZXJfMjMtMjE1MDYx/MTc4My5qcGc_c2Vt/dD1haXNfaHlicmlk/Jnc9NzQwJnE9ODA" className="size-10" square alt="" />
                  <span className="min-w-0">
                    <span className="block truncate text-sm/5 font-medium text-zinc-950 dark:text-white">
                      {user?.Vorname || 'Vorname'}
                    </span>
                    <span className="block truncate text-xs/5 font-normal text-zinc-500 dark:text-zinc-400">
                      {user?.email}
                    </span>
                  </span>
                </span>
                <ChevronUpIcon />
              </DropdownButton>
              <DropdownMenu className="min-w-64" anchor="top start">
                <DropdownItem href="/MeinProfil">
                  <UserIcon />
                  <DropdownLabel>{t('myProfile')}</DropdownLabel>
                </DropdownItem>
                <DropdownItem href="/settings">
                  <Cog8ToothIcon />
                  <DropdownLabel>{t('settings')}</DropdownLabel>
                </DropdownItem>
                <DropdownDivider />
                <DropdownItem href="/privacy-policy">
                  <ShieldCheckIcon />
                  <DropdownLabel>{t('privacyPolicy')}</DropdownLabel>
                </DropdownItem>
                <DropdownItem href="/share-feedback">
                  <LightBulbIcon />
                  <DropdownLabel>{t('shareFeedback')}</DropdownLabel>
                </DropdownItem>
                <DropdownDivider />
                <DropdownItem href="/logout">
                  <ArrowRightStartOnRectangleIcon />
                  <DropdownLabel>{t('signOut')}</DropdownLabel>
                </DropdownItem>
              </DropdownMenu>
            </Dropdown>
          </SidebarFooter>
        </Sidebar>
      }
    >
      <Outlet />
    </SidebarLayout>
  )
}
