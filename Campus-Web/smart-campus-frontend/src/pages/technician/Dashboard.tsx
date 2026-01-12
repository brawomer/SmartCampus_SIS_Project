import DashboardLayout from "../../layouts/DashboardLayout";
import { assets } from "../../data/assets";

const TechnicianDashboard = () => {
  return (
    <DashboardLayout>
      <h1 className="text-2xl font-bold mb-6">
        Technician Dashboard
      </h1>

      <div className="bg-white rounded-xl shadow overflow-hidden">
        <table className="w-full">
          <thead className="bg-gray-100">
            <tr>
              <th className="text-left p-4">Asset</th>
              <th className="text-left p-4">Asset Tag</th>
              <th className="text-left p-4">Room</th>
              <th className="text-left p-4">Status</th>
            </tr>
          </thead>
          <tbody>
            {assets.map((asset) => (
              <tr key={asset.assetId} className="border-t">
                <td className="p-4">{asset.assetName}</td>
                <td className="p-4">{asset.assetTag}</td>
                <td className="p-4">{asset.room}</td>
                <td className="p-4">
                  <span
                    className={`px-3 py-1 rounded-full text-sm font-medium
                      ${
                        asset.status === "Functional"
                          ? "bg-green-100 text-green-700"
                          : asset.status === "Broken"
                          ? "bg-red-100 text-red-700"
                          : "bg-yellow-100 text-yellow-700"
                      }`}
                  >
                    {asset.status}
                  </span>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </DashboardLayout>
  );
};

export default TechnicianDashboard;
