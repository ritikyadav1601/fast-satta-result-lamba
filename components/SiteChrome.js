import Link from "next/link";
import { getSettings } from "@/lib/store";

// Scrolling header text (same on every page). Replaces the old database text
// ("…Leak Numbers… 2019 Chart…"), which had no admin field to edit it.
const MARQUEE_TEXT =
  "Fast Satta Result Today – Check Latest Available Satta Results for Gali, Disawar, Ghaziabad, Faridabad & More | Explore Daily Updates, Satta King Charts and Historical Result Records in One Place.";

export function Header({ active = "home", settings }) {
  return (
    <section className="topboxnew">
      <div className="container-fluid">
        <div className="col-md-16 nopadding">
          <nav className="newnav">
            <ul>
              {[
                ["home", "/", "Home"],
                ["chart", "/chart", "Chart"],
                ["contact", "/contact", "Contact"],
                ["login", "/admin/login", "Login"],
              ].map(([key, href, label]) => (
                <li key={key}>
                  <Link className={active === key ? "active" : ""} href={href}>
                    {label}
                  </Link>
                </li>
              ))}
            </ul>
            <div className="clearfix" />
          </nav>
          <div className="text_slide">
            <marquee style={{ color: "#fff" }}>
              <p style={{ fontSize: 16, textAlign: "center" }}>
                {MARQUEE_TEXT}
              </p>
            </marquee>
          </div>
        </div>
      </div>
    </section>
  );
}

export function Footer({ settings }) {
  return (
    <footer>
      <section className="somelinks">
        <Link className="yellow-link mx-4" href="/privacy">
          Privacy Policy
        </Link>
        <Link className="yellow-link" href="/terms-and-conditions">
          Terms &amp; Conditions
        </Link>
        <Link className="yellow-link mx-4" href="/blogs">
          Blogs
        </Link>
      </section>
      <section className="somelinks2">
        <div className="container">
          <div className="row">
            <div className="col-md-12 text-center">
              <strong>
                ©️ 2024 {settings.website_name} All Rights Reserved
              </strong>
            </div>
          </div>
        </div>
      </section>
      <section className="somelinks">
        <div className="container">
          <div className="row">
            <div className="col-md-12 text-center">
              <ul>
                <li style={{ color: "#ffd800", padding: 0, fontWeight: 700 }}>
                  !! DISCLAIMER - {settings.disclaimer}
                </li>
              </ul>
            </div>
          </div>
        </div>
      </section>
    </footer>
  );
}

// Floating WhatsApp button (bottom centre, red/green animation; styles in app/globals.css).
// Uses the khaiwal WhatsApp number from the admin panel; hidden when none is set.
function FloatingWhatsApp({ settings }) {
  const number = String(settings?.whatsapp_number || "").replace(/\D/g, "");
  if (!number) return null;
  return (
    <a
      className="wa-float"
      data-wa-location="floating"
      href={`https://wa.me/${number}`}
      target="_blank"
      rel="noopener noreferrer"
      aria-label="Chat on WhatsApp"
    >
      <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true">
        <path
          fill="currentColor"
          d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.2-.4.2-.4.7-1.3.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.3.8 3.2.6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2-.1-.1-.3-.2-.5-.3z"
        />
      </svg>
      <span>WhatsApp करें</span>
    </a>
  );
}

export default async function SiteChrome({
  children,
  active,
  settings: providedSettings,
}) {
  const settings = providedSettings || (await getSettings());
  return (
    <>
      <Header active={active} settings={settings} />
      {children}
      <Footer settings={settings} />
      <FloatingWhatsApp settings={settings} />
    </>
  );
}
