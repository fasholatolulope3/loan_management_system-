import { cn } from "@/lib/utils"
import { Avatar, AvatarImage } from "@/components/ui/avatar"

export interface TestimonialAuthor {
  name: string
  handle: string
  avatar: string
}

export interface TestimonialCardProps {
  author: TestimonialAuthor
  text: string
  href?: string
  className?: string
}

export function TestimonialCard({ 
  author,
  text,
  href,
  className
}: TestimonialCardProps) {
  const Card = href ? 'a' : 'div'
  
  return (
    <Card
      {...(href ? { href } : {})}
      className={cn(
        "flex flex-col rounded-lg border-t border-slate-700",
        "bg-gradient-to-b from-slate-800/80 to-slate-900/50 backdrop-blur-md",
        "p-4 text-start sm:p-6",
        "hover:from-slate-700/80 hover:to-slate-800/50",
        "max-w-[320px] sm:max-w-[320px]",
        "transition-all duration-300 hover:scale-[1.02]",
        className
      )}
    >
      <div className="flex items-center gap-3">
        <Avatar className="h-12 w-12 border border-slate-600">
          <AvatarImage src={author.avatar} alt={author.name} />
        </Avatar>
        <div className="flex flex-col items-start">
          <h3 className="text-md font-semibold leading-none text-slate-200">
            {author.name}
          </h3>
          <p className="text-sm text-slate-400">
            {author.handle}
          </p>
        </div>
      </div>
      <p className="sm:text-md mt-4 text-sm text-slate-300 leading-relaxed">
        {text}
      </p>
    </Card>
  )
}
