<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Address;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(10);
        return view('user.orders.index', compact('orders'));
    }

    public function order_details($order_id)
    {
        $order = Order::where('user_id', Auth::user()->id)->where('id', $order_id)->first();
        if ($order) {
            $orderItems = OrderItem::where('order_id', $order->id)->orderBy('id')->paginate(12);
            $transaction = Transaction::where('order_id', $order->id)->first();
            return view('user.orders.details', compact('order', 'orderItems', 'transaction'));
        } else {
            return redirect()->route('login');
        }
    }

    public function order_cancel(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->status = 'canceled';
        $order->canceled_date = Carbon::now();
        $order->save();
        return back()->with('status', 'Order has been cancelled successfully !');
    }

    public function account_details()
    {
        $user = Auth::user();
        return view('user.account-details', compact('user'));
    }

    public function account_update(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'required|numeric|digits:10|unique:users,mobile,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name   = $request->name;
        $user->mobile = $request->mobile;
        $user->email  = $request->email;

        if ($request->hasFile('avatar')) {
            if ($user->avatar && file_exists(public_path('uploads/avatars/' . $user->avatar))) {
                unlink(public_path('uploads/avatars/' . $user->avatar));
            }
            $file = $request->file('avatar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $filename);
            $user->avatar = $filename;
        }

        if ($request->filled('new_password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->with('error', 'The old password entered is incorrect.');
            }
            if ($request->new_password !== $request->new_password_confirmation) {
                return back()->with('error', 'New password and confirm password do not match.');
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Updated successfully.');
    }

    public function addresses()
    {
        $addresses = Address::where('user_id', Auth::id())->get();
        return view('user.addresses.index', compact('addresses'));
    }

    public function address_add()
    {
        return view('user.addresses.add');
    }

    public function address_store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:20',
            'zip'      => 'required|string|max:20',
            'state'    => 'required|string|max:100',
            'city'     => 'required|string|max:100',
            'country'  => 'required|string|max:100',
            'address'  => 'required|string',
            'locality' => 'required|string',
            'landmark' => 'nullable|string',
        ]);

        if ($request->isdefault) {
            Address::where('user_id', Auth::id())->update(['isdefault' => 0]);
        }

        Address::create([
            'user_id'   => Auth::id(),
            'name'      => $request->name,
            'phone'     => $request->phone,
            'zip'       => $request->zip,
            'state'     => $request->state,
            'city'      => $request->city,
            'country'   => $request->country,
            'address'   => $request->address,
            'locality'  => $request->locality,
            'landmark'  => $request->landmark,
            'isdefault' => $request->isdefault ? 1 : 0,
        ]);

        return redirect()->route('user.addresses.index')->with('success', 'Address has been added successfully.');
    }

    public function address_edit($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        return view('user.addresses.edit', compact('address'));
    }

    public function address_update(Request $request)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($request->id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:20',
            'zip'      => 'required|string|max:20',
            'state'    => 'required|string|max:100',
            'city'     => 'required|string|max:100',
            'country'  => 'required|string|max:100',
            'address'  => 'required|string',
            'locality' => 'required|string',
            'landmark' => 'nullable|string',
        ]);

        if ($request->isdefault) {
            Address::where('user_id', Auth::id())->update(['isdefault' => 0]);
        }

        $address->name      = $request->name;
        $address->phone     = $request->phone;
        $address->zip       = $request->zip;
        $address->state     = $request->state;
        $address->city      = $request->city;
        $address->country   = $request->country;
        $address->address   = $request->address;
        $address->locality  = $request->locality;
        $address->landmark  = $request->landmark;
        $address->isdefault = $request->isdefault ? 1 : 0;
        $address->save();

        return redirect()->route('user.addresses.index')->with('success', 'Address has been updated successfully.');
    }

    public function address_delete($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();
        return redirect()->route('user.addresses.index')->with('success', 'Address deleted successfully.');
    }
}