"use client";

import Script from "next/script";
import { usePathname } from "next/navigation";
import { useEffect } from "react";

// Google Analytics 4 — property "fast-satta-result.com", stream "Fast Satta Result Website".
export const GA_MEASUREMENT_ID = "G-T31HZSV0TY";

// Admin pages are not tracked.
const isAdminPath = path => /^\/(admin|result-admin)(\/|$)/.test(path || "");

export default function GoogleAnalytics() {
  const pathname = usePathname();
  const skip = isAdminPath(pathname);

  // One listener for every WhatsApp link on the site (floating button, under
  // Disawar, khaiwal box, footer). Sends the GA4 event "whatsapp_click" with
  // button_location (from data-wa-location on the link) and page_path.
  useEffect(() => {
    if (skip) return;
    const onClick = event => {
      const link = event.target?.closest?.(
        'a[href*="wa.me"], a[href*="whatsapp.com"], a[href*="api.whatsapp"]'
      );
      if (!link || typeof window.gtag !== "function") return;
      window.gtag("event", "whatsapp_click", {
        button_location: link.dataset.waLocation || "other",
        link_url: link.href,
        page_path: window.location.pathname,
        transport_type: "beacon",
      });
    };
    document.addEventListener("click", onClick, true);
    return () => document.removeEventListener("click", onClick, true);
  }, [skip]);

  if (skip) return null;
  return (
    <>
      <Script
        src={`https://www.googletagmanager.com/gtag/js?id=${GA_MEASUREMENT_ID}`}
        strategy="afterInteractive"
      />
      <Script id="ga4-init" strategy="afterInteractive">
        {`window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}window.gtag=gtag;gtag('js',new Date());gtag('config','${GA_MEASUREMENT_ID}');`}
      </Script>
    </>
  );
}
