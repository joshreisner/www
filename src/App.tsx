import React from "react";

export default function App() {
  return (
    <div className="flex h-screen bg-gradient-to-br from-blue-50 to-blue-200">
      <div className="m-auto p-5 md:p-10">
        <div className="p-6 max-w-sm bg-white rounded-xl shadow-md flex space-x-4">
          <div className="flex-shrink-0">
            <img
              className="h-20 w-20 rounded-md"
              src="/josh.jpg"
              alt="Josh Reisner"
            />
          </div>
          <div>
            <div className="text-xl font-medium text-black">Josh Reisner</div>
            <p className="text-gray-500">
              <>I am a Senior Web Developer at </>
              <a
                className="underline text-blue-400"
                href="https://www.atlassian.com/"
              >
                Atlassian
              </a>
              <>, working on </>
              <a className="underline text-blue-400" href="https://trello.com/">
                Trello
              </a>
              <>.</>
            </p>
          </div>
        </div>
      </div>
    </div>
  );
}
