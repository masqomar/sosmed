import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Head, usePage } from '@inertiajs/react';
import { useMemo, useState } from 'react';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'TikTok Accounts', href: '/accounts/tiktok' },
];

export default function TikTokAccountsPage() {
  const { props } = usePage<any>();
  const { stats, accounts } = props;
  const [query, setQuery] = useState('');
  const filteredAccounts = useMemo(() => {
    if (!Array.isArray(accounts)) return [];
    return accounts.filter((a: any) =>
      (a.name ?? '').toLowerCase().includes(query.toLowerCase())
    );
  }, [accounts, query]);

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="TikTok Accounts" />
      <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4">
        {/* Header */}
        <div className="flex items-start justify-between">
          <div className="space-y-1">
            <div className="flex items-center gap-2">
              <span className="inline-flex size-6 items-center justify-center rounded-md bg-[#000000] text-white">
                {/* TikTok glyph-like placeholder */}
                <span className="text-xs font-bold">TT</span>
              </span>
              <h1 className="text-2xl font-semibold tracking-tight">TikTok Accounts</h1>
            </div>
            <p className="text-sm text-muted-foreground">
              Manage your TikTok social media accounts and connections
            </p>
          </div>
          <Button asChild>
            <a href="/auth/tiktok/redirect">+ Add TikTok Account</a>
          </Button>
        </div>

        {/* Stats cards */}
        <div className="grid gap-4 md:grid-cols-3">
          <div className="rounded-xl border border-sidebar-border/70 bg-background p-6 shadow-xs dark:border-sidebar-border">
            <div className="flex items-center gap-3">
              <span className="inline-flex size-8 items-center justify-center rounded-md bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                ✓
              </span>
              <div>
                <div className="text-2xl font-semibold leading-tight">1</div>
                <div className="text-sm text-muted-foreground">Connected</div>
              </div>
            </div>
          </div>

          <div className="rounded-xl border border-sidebar-border/70 bg-background p-6 shadow-xs dark:border-sidebar-border">
            <div className="flex items-center gap-3">
              <span className="inline-flex size-8 items-center justify-center rounded-md bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                !
              </span>
              <div>
                <div className="text-2xl font-semibold leading-tight">0</div>
                <div className="text-sm text-muted-foreground">Pending Connection</div>
              </div>
            </div>
          </div>

          <div className="rounded-xl border border-sidebar-border/70 bg-background p-6 shadow-xs dark:border-sidebar-border">
            <div className="flex items-center gap-3">
              <span className="inline-flex size-8 items-center justify-center rounded-md bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                👥
              </span>
              <div>
                <div className="text-2xl font-semibold leading-tight">1</div>
                <div className="text-sm text-muted-foreground">Total Accounts</div>
              </div>
            </div>
          </div>
        </div>

        {/* Search */}
        <div className="flex items-center gap-4">
          <div className="relative w-full max-w-xl">
            <span className="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">🔍</span>
            <Input
              className="pl-9"
              placeholder="Search accounts..."
            />
          </div>
        </div>

        {/* Accounts list */}
        <div className="grid gap-4">
          {/* Account card */}
          <div className="rounded-xl border border-sidebar-border/70 bg-background p-6 shadow-xs dark:border-sidebar-border">
            <div className="flex items-start justify-between gap-4">
              <div className="flex items-start gap-4">
                {/* Avatar */}
                <div className="relative inline-flex size-12 items-center justify-center overflow-hidden rounded-full border border-sidebar-border/70 bg-muted/50">
                  <span className="text-lg font-semibold">S</span>
                </div>

                {/* Title and badges */}
                <div className="space-y-2">
                  <div className="flex items-center gap-2">
                    <h2 className="text-xl font-semibold">Syahid</h2>
                    {/* platform glyph */}
                    <span className="inline-flex size-5 items-center justify-center rounded-sm bg-[#000000] text-white text-[10px] font-bold">TT</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <span className="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                      Connected
                    </span>
                    <span className="inline-flex items-center rounded-full bg-muted px-3 py-1 text-xs font-medium text-foreground/70">
                      Page
                    </span>
                  </div>
                </div>
              </div>

              {/* Right-side CTA (optional) */}
              <div className="flex items-center gap-2">
                <Button asChild variant="outline" size="sm">
                  <a href="/tiktok/user">View Info</a>
                </Button>
              </div>
            </div>

            {/* Metrics */}
            <div className="mt-6 grid gap-6 sm:grid-cols-3">
              <div className="flex items-center gap-3">
                <span className="inline-flex size-8 items-center justify-center rounded-md bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">👥</span>
                <div>
                  <div className="text-base font-semibold">0</div>
                  <div className="text-xs text-muted-foreground">Followers</div>
                </div>
              </div>

              <div className="flex items-center gap-3">
                <span className="inline-flex size-8 items-center justify-center rounded-md bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300">📈</span>
                <div>
                  <div className="text-base font-semibold">0</div>
                  <div className="text-xs text-muted-foreground">Engagement</div>
                </div>
              </div>

              <div className="flex items-center gap-3">
                <span className="inline-flex size-8 items-center justify-center rounded-md bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">🕒</span>
                <div>
                  <div className="text-base font-semibold">10 hours ago</div>
                  <div className="text-xs text-muted-foreground">Last Activity</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}