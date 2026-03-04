import * as React from "react";
import MegaMenu from "@/components/ui/mega-menu";
import type { MegaMenuItem } from "@/components/ui/mega-menu";
import {
  Cpu,
  Globe,
  Eye,
  Shield,
  Rocket,
  Box,
  Search,
  Palette,
  BookOpen,
  FileText,
  Newspaper,
} from "lucide-react";

export const NAV_ITEMS: MegaMenuItem[] = [
  {
    id: 1,
    label: "Products",
    subMenus: [
      {
        title: "Loan Suite",
        items: [
          {
            label: "Personal Loans",
            description: "Flexible financing for your needs",
            icon: Cpu,
          },
          {
            label: "Business Loans",
            description: "Empower your business growth",
            icon: Search,
          },
        ],
      },
      {
        title: "Managed Infrastructure",
        items: [
          {
            label: "PDEI Portal",
            description: "Secure administrative access",
            icon: Shield,
          },
          {
            label: "Audit Logs",
            description: "Trace every transaction",
            icon: Eye,
          },
        ],
      },
    ],
  },
  {
    id: 2,
    label: "Solutions",
    subMenus: [
      {
        title: "Use Cases",
        items: [
          {
            label: "Micro-financing",
            description: "Small loans, big impact",
            icon: Rocket,
          },
          {
            label: "Asset Financing",
            description: "Acquire what you need today",
            icon: Box,
          },
        ],
      },
    ],
  },
  {
    id: 3,
    label: "Resources",
    subMenus: [
      {
        title: "Company",
        items: [
          {
            label: "Blog",
            description: "Latest news and updates",
            icon: Newspaper,
          },
          {
            label: "Contact",
            description: "Get in touch with us",
            icon: Globe,
          },
        ],
      },
    ],
  },
  { id: 4, label: "Enterprise", link: "#" },
  { id: 5, label: "Pricing", link: "#" },
];

const MegaMenuDemo = () => {
  return (
    <div className="relative flex w-full items-start justify-center p-10">
      <MegaMenu items={NAV_ITEMS} />
    </div>
  );
};

export { MegaMenuDemo as DemoOne };
