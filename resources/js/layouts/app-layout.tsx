import React from "react";
import AppHeader from "@/components/app-header";

export default ({ children }: { children: React.ReactElement }) => (
    <div className="bg-muted">
        <AppHeader />
        {children}
    </div>
);
