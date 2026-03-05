import * as React from "react";
import MegaMenu from "@/components/ui/mega-menu";
import type { MegaMenuItem } from "@/components/ui/mega-menu";
import {
  Rocket,
  Shield,
  FileText,
  Lock,
  MessageCircle,
  TrendingUp,
  CreditCard,
  Building2,
  Users,
  Target,
  Scale,
  Gavel
} from "lucide-react";

export const NAV_ITEMS: MegaMenuItem[] = [
  { id: 1, label: "About Us", link: "#about" },
  {
    id: 2,
    label: "Solutions",
    subMenus: [
      {
        title: "Services",
        items: [
          {
            label: "Core Features",
            description: "Structured lending & investment",
            icon: Target,
            link: "#features"
          },
          {
            label: "Pricing & Terms",
            description: "Transparent risk-based framework",
            icon: CreditCard,
            link: "#pricing"
          },
        ],
      },
      {
        title: "Support",
        items: [
          {
            label: "FAQs",
            description: "Common questions answered",
            icon: MessageCircle,
            link: "#faqs"
          },
          {
            label: "Testimonials",
            description: "What our clients say",
            icon: Users,
            link: "#testimonials"
          },
        ],
      },
    ],
  },
  {
    id: 3,
    label: "Legal",
    subMenus: [
      {
        title: "Policies",
        items: [
          {
            label: "Legal Status",
            description: "Regulatory status & governance",
            icon: Scale,
            link: "#legal"
          },
          {
            label: "Terms of Service",
            description: "Agreement governing use",
            icon: Gavel,
            link: "#terms"
          },
          {
            label: "Privacy Policy",
            description: "Data protection & privacy",
            icon: Shield,
            link: "#privacy"
          },
        ],
      },
    ],
  },
  { id: 4, label: "Contact", link: "#faqs" },
];

const MegaMenuDemo = () => {
  return (
    <div className="relative flex w-full items-start justify-center p-10">
      <MegaMenu items={NAV_ITEMS} />
    </div>
  );
};

export { MegaMenuDemo as DemoOne };
