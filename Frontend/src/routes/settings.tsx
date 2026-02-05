import { useState, useEffect } from 'react'
import { useTranslation } from 'react-i18next'
import { useSearchParams } from 'react-router'
import { Heading } from '@catalyst/heading'
import { Divider } from '@catalyst/divider'
import { Field, Label } from '@catalyst/fieldset'
import { Input } from '@catalyst/input'
import { Button } from '@catalyst/button'

export default function Settings() {
  const { t, i18n } = useTranslation()
  const [searchParams] = useSearchParams()
  const [username, setUsername] = useState('')
  const [showResetForm, setShowResetForm] = useState(false)
  const [token, setToken] = useState('')
  const [newPassword, setNewPassword] = useState('')
  const [confirmPassword, setConfirmPassword] = useState('')
  const [themeMode, setThemeMode] = useState<'light' | 'dark' | 'system'>(() => {
    const saved = localStorage.getItem('themeMode')
    return (saved as 'light' | 'dark' | 'system') || 'system'
  })
  const [language, setLanguage] = useState(() => {
    return localStorage.getItem('language') || 'de'
  })

  useEffect(() => {
    const saved = localStorage.getItem('language')
    if (saved && i18n.language !== saved) {
      i18n.changeLanguage(saved)
      setLanguage(saved)
    }
  }, [i18n])

  const changeLanguage = (lang: string) => {
    i18n.changeLanguage(lang)
    localStorage.setItem('language', lang)
    setLanguage(lang)
  }

  useEffect(() => {
    const urlToken = searchParams.get('token')
    if (urlToken) {
      setToken(urlToken)
      setShowResetForm(true)
    }
  }, [searchParams])

  useEffect(() => {
    const applyTheme = () => {
      if (themeMode === 'system') {
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
        if (systemPrefersDark) {
          document.documentElement.classList.add('dark')
        } else {
          document.documentElement.classList.remove('dark')
        }
      } else if (themeMode === 'dark') {
        document.documentElement.classList.add('dark')
      } else {
        document.documentElement.classList.remove('dark')
      }
    }

    applyTheme()
    localStorage.setItem('themeMode', themeMode)

    // Listen to system theme changes when in system mode
    if (themeMode === 'system') {
      const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
      const handleChange = () => applyTheme()
      mediaQuery.addEventListener('change', handleChange)
      return () => mediaQuery.removeEventListener('change', handleChange)
    }
  }, [themeMode])

  const sendPasswordReset = async () => {
    if (!username) return alert(t('enterUsername'))

    try {
      const response = await fetch('http://localhost/Passwort/Vergessen', {
        method: 'POST',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username }),
      })

      const data = await response.json()
      if (response.ok) {
        alert(t('tokenSent'))
        setShowResetForm(true)
      } else {
        alert(t('tokenError') + ': ' + (data.message || ''))
      }
    } catch (err) {
      console.error(err)
      alert(t('tokenError'))
    }
  }

  const resetPassword = async () => {
    if (!token || !newPassword || !confirmPassword) return alert(t('fillAllFields'))
    if (newPassword !== confirmPassword) return alert(t('passwordMismatch'))

    try {
      // FormData für PHP-Endpoint verwenden
      const formData = new FormData()
      formData.append('password', newPassword)
      formData.append('password_bestaetigen', confirmPassword)

      const response = await fetch(`http://localhost/passwort-vergessen/token?token=${token}`, {
        method: 'POST',
        body: formData,
      })

      // PHP gibt HTML zurück, nicht JSON - prüfen ob erfolgreich
      if (response.ok) {
        alert(t('passwordChanged'))
        // Felder zurücksetzen
        setUsername('')
        setToken('')
        setNewPassword('')
        setConfirmPassword('')
        setShowResetForm(false)
      } else {
        alert(t('passwordChangeError'))
      }
    } catch (err) {
      console.error(err)
      alert(t('passwordChangeError'))
    }
  }

  return (
    <div className="mx-auto max-w-4xl p-4">
      <Heading>{t('einstellungen')}</Heading>
      <Divider className="my-6" />

      {/* Theme Toggle */}
      <Field>
        <Label>{t('theme')}</Label>
        <div className="relative inline-flex rounded-lg p-1 bg-zinc-200 dark:bg-zinc-700">
          {/* Sliding indicator */}
          <div
            className={`absolute top-1 bottom-1 w-[calc(33.333%-0.333rem)] bg-white dark:bg-zinc-800 rounded-md shadow-sm transition-transform duration-200 ease-in-out ${themeMode === 'light'
              ? 'translate-x-0'
              : themeMode === 'system'
                ? 'translate-x-[calc(100%+0.5rem)]'
                : 'translate-x-[calc(200%+1rem)]'
              }`}
          />

          {/* Light Mode Button */}
          <button
            type="button"
            onClick={() => setThemeMode('light')}
            className={`relative z-10 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 ${themeMode === 'light' ? 'text-zinc-900 dark:text-black' : 'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300'
              }`}
          >
            Light
          </button>

          {/* System Mode Button */}
          <button
            type="button"
            onClick={() => setThemeMode('system')}
            className={`relative z-10 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 ${themeMode === 'system' ? 'text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300'
              }`}
          >
            System
          </button>

          {/* Dark Mode Button */}
          <button
            type="button"
            onClick={() => setThemeMode('dark')}
            className={`relative z-10 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 ${themeMode === 'dark' ? 'text-zinc-900 dark:text-white' : 'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400'
              }`}
          >
            Dark
          </button>
        </div>
      </Field>

      <Divider className="my-6" />

      {/* Language Toggle */}
      <Field>
        <Label>{t('languageLabel')}</Label>
        <div className="relative inline-flex rounded-lg p-1 bg-zinc-200 dark:bg-zinc-700">
          {/* Sliding indicator */}
          <div
            className={`absolute top-1 bottom-1 w-[calc(50%-0.25rem)] bg-white dark:bg-zinc-800 rounded-md shadow-sm transition-transform duration-200 ease-in-out ${language === 'de' ? 'translate-x-0' : 'translate-x-[calc(100%+0.5rem)]'
              }`}
          />

          {/* German Button */}
          <button
            type="button"
            onClick={() => changeLanguage('de')}
            className={`relative z-10 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 ${language === 'de' ? 'text-zinc-900 dark:text-white' : 'text-zinc-700 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-300'
              }`}
          >
            🇩🇪 Deutsch
          </button>

          {/* English Button */}
          <button
            type="button"
            onClick={() => changeLanguage('en')}
            className={`relative z-10 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 ${language === 'en' ? 'text-zinc-900 dark:text-white' : 'text-zinc-700 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-300'
              }`}
          >
            🇬🇧 English
          </button>
        </div>
      </Field>

      <Divider className="my-6" />

      <Heading>{t('passwordReset')}</Heading>


      {/* Username-Feld */}
      <Field>
        <Label>{t('E-Mail')}</Label>
        <Input value={username} onChange={(e) => setUsername(e.target.value)} />
      </Field>
      <Button type="button" onClick={sendPasswordReset}>{t('passwordResetButton')}</Button>

      {/* Token & neue Passwörter */}
      {showResetForm && (
        <>
          <Field>
            <Label>{t('token')}</Label>
            <Input value={token} onChange={(e) => setToken(e.target.value)} />
          </Field>
          <Field>
            <Label>{t('newPassword')}</Label>
            <Input type="password" value={newPassword} onChange={(e) => setNewPassword(e.target.value)} />
          </Field>
          <Field>
            <Label>{t('confirmPassword')}</Label>
            <Input type="password" value={confirmPassword} onChange={(e) => setConfirmPassword(e.target.value)} />
          </Field>
          <Button type="button" onClick={resetPassword}>{t('changePassword')}</Button>
        </>
      )}
    </div>
  )
}
