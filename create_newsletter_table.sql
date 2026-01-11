-- Create newsletter_subscriptions table
CREATE TABLE IF NOT EXISTS newsletter_subscriptions (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    email TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now()) NOT NULL
);

-- Enable RLS
ALTER TABLE newsletter_subscriptions ENABLE ROW LEVEL SECURITY;

-- Allow public inserts (so anyone can subscribe)
CREATE POLICY "Allow public subscriptions" ON newsletter_subscriptions
    FOR INSERT WITH CHECK (true);

-- Allow admins to view subscribers (using service role)
-- By default, service role bypasses RLS, so no extra policy needed for admin.
