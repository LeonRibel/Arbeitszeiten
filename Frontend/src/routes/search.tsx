import { useState, useEffect } from "react"
import { MagnifyingGlassIcon } from '@heroicons/react/20/solid'
import { Link, useNavigate } from "react-router-dom"
import { navigationItems } from "./NavItem" 
import { useTranslation } from 'react-i18next'
console.log('navigationItems import (search):', navigationItems)

export default function Search() {
  const { t } = useTranslation()
  const [searchTerm, setSearchTerm] = useState("")
  const navigate = useNavigate()

  useEffect(() => {
    navigate('/', { replace: true })
  }, [navigate])

  console.log('navigationItems (from NavItem.tsx):', navigationItems)
  const filteredItems = Array.isArray(navigationItems) ? navigationItems : []

  return (
    <main className="max-w-3xl mx-auto">
      <h1 className="text-2xl font-semibold mb-6">{t('quickSearch')}</h1>

      <div className="relative mb-8">
        <MagnifyingGlassIcon className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-zinc-400" />
        <input
          type="text"
          placeholder={t('searchPages')}
          value={searchTerm}
          onChange={(e) => setSearchTerm(e.target.value)}
          autoFocus
          className="w-full pl-12 pr-4 py-3 text-lg border border-zinc-300 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <div className="space-y-2">
        {filteredItems.length > 0 ? (
          filteredItems.map((item) => {
            const Icon = item.icon
            return (
              <Link
                key={item.href}
                to={item.href}
                className="block p-4 rounded-lg border border-zinc-200 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors group"
              >
                <div className="flex items-start gap-4">
                  <div className="p-2 rounded-lg bg-zinc-100 dark:bg-zinc-800 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 transition-colors">
                    <Icon className="w-6 h-6 text-zinc-600 dark:text-zinc-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" />
                  </div>
                  <div className="flex-1 min-w-0">
                    <h3 className={`font-medium text-zinc-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 ${item.className ?? ''}`}>
                      {item.name}
                    </h3>
                    <p className={`text-sm text-zinc-500 dark:text-zinc-400 mt-1 ${item.className ?? ''}`}>
                      {item.description}
                    </p>
                  </div>
                </div>
              </Link>
            )
          })
        ) : (
          <div className="text-center py-12 text-zinc-500 dark:text-zinc-400">
            <MagnifyingGlassIcon className="w-12 h-12 mx-auto mb-3 opacity-50" />
            <p>{t('noResultsFor')} "{searchTerm}"</p>
          </div>
        )}
      </div>
    </main>
  )
}
