import { TestimonialsSection } from "@/components/blocks/testimonials-with-marquee"


const testimonials = [
  {
    author: {
      name: "Chukwudi Okafor",
      handle: "@chuks_biz",
      avatar: "/images/avatars/chukwudi.png"
    },
    text: "The loan application process was seamless. I received funding for my business expansion within 24 hours. Highly recommended!",
    href: "#"
  },
  {
    author: {
      name: "Aisha Bello",
      handle: "@aisha_invest",
      avatar: "/images/avatars/aisha.png"
    },
    text: "PDEC's investment plans are transparent and reliable. My portfolio has grown steadily, and the support team is always helpful.",
    href: "#"
  },
  {
    author: {
      name: "Oluwaseun Ajayi",
      handle: "@seun_ajayi",
      avatar: "/images/avatars/seun.png"
    },
    text: "Finally, a financial partner that understands the needs of Nigerian entrepreneurs. The low interest rates are a game changer."
  }
]

export function TestimonialsSectionDemo() {
  return (
    <TestimonialsSection
      title="Trusted by thousands of clients"
      description="Join the community of entrepreneurs and individuals who have unlocked their financial potential with PDEC."
      testimonials={testimonials}
    />
  )
}
